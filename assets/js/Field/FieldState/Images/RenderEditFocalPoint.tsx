import * as React from 'react';
import { useRenderImageContext } from './RenderImageContext';

const RenderEditFocalPointInner = () => {
    console.log('todo: edit focal point');

    return <>todo: edit focal point</>;
};

const RenderEditFocalPoint = () => {
    const { focalPointIsOpen } = useRenderImageContext();

    if (!focalPointIsOpen) {
        return <></>;
    }

    return <RenderEditFocalPointInner />;
};

export default RenderEditFocalPoint;
