import * as React from 'react';
import ReactCrop from 'react-image-crop';
import { useEffect, useRef, useState } from 'react';
import { MdClose } from 'react-icons/md';
import { BsCheck } from 'react-icons/bs';
import { Portal } from 'react-portal';
import { useRenderImageContext } from './RenderImageContext';
import { useFieldSettings } from '../../FieldSettings/FieldSettingsContext';

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

    const cancelCrop = (event: Event|React.MouseEvent) => {
        event.preventDefault();

        setCrop(acceptedCrop);

        setCropIsOpen(() => false);
    };

    const acceptCrop = (event: Event|React.MouseEvent) => {
        event.preventDefault();

        setAcceptedCrop({ ...crop });

        setCropIsOpen(() => false);
    };

    useEffect(() => {
        const handleKeyDown = (event: KeyboardEvent) => {
            if (event.code === 'Escape') {
                cancelCrop(event);

                return;
            }

            if (event.code === 'Enter' || event.code === 'NumpadEnter') {
                acceptCrop(event);
            }
        };

        window.addEventListener('keydown', handleKeyDown);

        return () => {
            window.removeEventListener('keydown', handleKeyDown);
        };
    });

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

    useEffect(() => {
        window.addEventListener('resize', handleResize);

        return () => {
            window.removeEventListener('resize', handleResize);
        };
    });

    useEffect(() => {
        handleResize();
    }, [imageIsLoaded]);

    const iconAnchorClasses = 'ansel_text-red-600 ansel_bg-gray-100 hover:ansel_bg-gray-200 ansel_h-40px ansel_w-70px ansel_flex ansel_flex-row ansel_items-center ansel_justify-center';

    return <Portal>
        <div className="ansel_fixed ansel_top-0 ansel_left-0 ansel_w-full ansel_h-full ansel_z-max ansel_bg-black-opacity-60">
            <div className="ansel_absolute ansel_top-0 ansel_left-0 ansel_w-full ansel_h-full ansel_flex ansel_items-center ansel_justify-center">
                <div>
                    <div className="ansel_relative">
                        <div>
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
                        </div>
                    </div>
                    <div className="ansel_flex ansel_flex-row ansel_items-center ansel_justify-center">
                        <a
                            onClick={cancelCrop}
                            href="#0"
                            className={`ansel_text-red-600 hover:ansel_text-red-600 ansel_rounded-l-lg ${iconAnchorClasses}`}
                        >
                            <MdClose size="22px" />
                        </a>
                        <div className="ansel_bg-gray-300 ansel_h-40px ansel_w-1px"></div>
                        <a
                            onClick={acceptCrop}
                            href="#0"
                            className={`ansel_text-green-500 hover:ansel_text-green-500 ansel_rounded-r-lg ${iconAnchorClasses}`}
                        >
                            <BsCheck size="30px" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </Portal>;
};

const RenderImageCrop = () => {
    const { cropIsOpen } = useRenderImageContext();

    if (!cropIsOpen) {
        return <></>;
    }

    return <RenderImageCropInner />;
};

export default RenderImageCrop;
