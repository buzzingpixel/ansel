import * as React from 'react';
import ReactCrop from 'react-image-crop';
import { useEffect, useRef, useState } from 'react';
import { useRenderImageContext } from './RenderImageContext';
import { useFieldSettings } from '../../FieldSettings/FieldSettingsContext';
import AnselPortal from '../../Utility/AnselPortal';
import useWindowResize from '../../../Hooks/useWindowResize';

const RenderImageCropInner = () => {
    const [
        imageIsLoaded,
        setImageIsLoaded,
    ] = useState<boolean>(false);

    const [minWidth, setMinWidth] = useState<number | null>(null);

    const [minHeight, setMinHeight] = useState<number | null>(null);

    const imageEl = useRef<HTMLImageElement | null>(null);

    const {
        ratioAsNumber,
        minWidth: settingMinWidth,
        minHeight: settingMinHeight,
    } = useFieldSettings();

    const {
        crop,
        image,
        setCrop,
        acceptedCrop,
        setCropIsOpen,
        setAcceptedCrop,
    } = useRenderImageContext();

    const accept = (event: Event|React.MouseEvent) => {
        event.preventDefault();

        setAcceptedCrop({ ...crop });

        setCropIsOpen(() => false);
    };

    const cancel = (event: Event|React.MouseEvent) => {
        event.preventDefault();

        setCrop(acceptedCrop);

        setCropIsOpen(() => false);
    };

    const handleResize = () => {
        if (!imageIsLoaded) {
            return;
        }

        const imgTag = imageEl.current as HTMLImageElement;

        if (settingMinWidth) {
            const widthReducer = imgTag.width / imgTag.naturalWidth;

            setMinWidth(Math.ceil(settingMinWidth * widthReducer));
        }

        if (settingMinHeight) {
            const heightReducer = imgTag.height / imgTag.naturalHeight;

            setMinHeight(Math.ceil(settingMinHeight * heightReducer));
        }
    };

    useWindowResize(() => {
        handleResize();
    });

    useEffect(() => {
        handleResize();
    }, [imageIsLoaded]);

    // TODO: Move heading to lang files
    return (
        <AnselPortal
            heading='Edit image crop. Press red button (or escape) to cancel. Press green button (or enter) to accept changes.'
            cancelAction={cancel}
            acceptAction={accept}
        >
            <ReactCrop
                crop={crop}
                onChange={(_, c) => setCrop(c)}
                aspect={ratioAsNumber}
                minWidth={minWidth}
                minHeight={minHeight}
            >
                <img
                    ref={imageEl}
                    src={image.imageUrl}
                    alt=""
                    onLoad={() => {
                        setImageIsLoaded(true);
                    }}
                />
            </ReactCrop>
        </AnselPortal>
    );
};

const RenderImageCrop = () => {
    const { cropIsOpen } = useRenderImageContext();

    if (!cropIsOpen) {
        return <></>;
    }

    return <RenderImageCropInner />;
};

export default RenderImageCrop;
