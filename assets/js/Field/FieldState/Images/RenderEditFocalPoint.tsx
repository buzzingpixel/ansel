import * as React from 'react';
import { useState } from 'react';
import { FocalPoint, useRenderImageContext } from './RenderImageContext';
import AnselPortal from '../../Utility/AnselPortal';

const RenderEditFocalPointInner = () => {
    const { image, setFocalPoint, setFocalPointIsOpen } = useRenderImageContext();

    const [localFocal, setLocalFocal] = useState<FocalPoint>({
        x: image.focalX,
        y: image.focalY,
    });

    const accept = (event: Event|React.MouseEvent) => {
        event.preventDefault();

        setFocalPoint({ ...localFocal });

        setFocalPointIsOpen(false);
    };

    const cancel = (event: Event|React.MouseEvent) => {
        event.preventDefault();

        setLocalFocal(() => ({
            x: image.focalX,
            y: image.focalY,
        }));

        setFocalPointIsOpen(false);
    };

    return (
        <AnselPortal accept={accept} cancel={cancel}>
            todo: edit focal point
        </AnselPortal>
    );
};

const RenderEditFocalPoint = () => {
    const { focalPointIsOpen } = useRenderImageContext();

    if (!focalPointIsOpen) {
        return <></>;
    }

    return <RenderEditFocalPointInner />;
};

export default RenderEditFocalPoint;
