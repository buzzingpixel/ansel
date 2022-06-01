import ReactCrop, { PercentCrop } from 'react-image-crop';
import { MdClose } from 'react-icons/md';
import { BsCheck } from 'react-icons/bs';
import * as React from 'react';
import { useEffect, useRef, useState } from 'react';
import ImageType from '../Types/ImageType';
import GetPixelCropFromPercentCrop from '../Utility/GetPixelCropFromPercentCrop';
import FieldStateType from '../Types/FieldStateType';
import FieldDataType from '../Types/FieldDataType';

const CropImage = ({
    crop,
    index,
    image,
    setCrop,
    fieldData,
    acceptedCrop,
    setCropIsOpen,
    setFieldState,
    setAcceptedCrop,
    setPixelCropState,
}: {
    crop: PercentCrop,
    index: number,
    image: ImageType,
    fieldData: FieldDataType,
    setCrop: CallableFunction,
    acceptedCrop: PercentCrop,
    setCropIsOpen: CallableFunction,
    setFieldState: CallableFunction,
    setAcceptedCrop: CallableFunction,
    setPixelCropState: CallableFunction,
}) => {
    const [imageIsLoaded, setImageIsLoaded] = useState<boolean>(false);

    const [minWidth, setMinWidth] = useState<number | null>(null);

    const [minHeight, setMinHeight] = useState<number | null>(null);

    const imageEl = useRef<HTMLImageElement | null>(null);

    const cancelCrop = (event: Event|React.MouseEvent) => {
        event.preventDefault();

        setCrop(acceptedCrop);

        setCropIsOpen(() => false);
    };

    const acceptCrop = (event: Event|React.MouseEvent) => {
        event.preventDefault();

        setAcceptedCrop({ ...crop });

        GetPixelCropFromPercentCrop(image, crop).then(
            (incomingCrop) => {
                setPixelCropState({ ...incomingCrop });

                setFieldState((oldFieldState: FieldStateType) => {
                    image.x = incomingCrop.x;
                    image.y = incomingCrop.y;
                    image.width = incomingCrop.width;
                    image.height = incomingCrop.height;

                    oldFieldState.images[index] = image;

                    return { ...oldFieldState };
                });
            },
        );

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

        const settingMinWidth = fieldData.fieldSettings.minWidth;

        if (settingMinWidth) {
            const widthReducer = imgTag.width / imgTag.naturalWidth;

            setMinWidth(Math.ceil(settingMinWidth * widthReducer));
        }

        const settingMinHeight = fieldData.fieldSettings.minHeight;

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

    return <div
        className="ansel_fixed ansel_top-0 ansel_left-0 ansel_w-full ansel_h-full ansel_z-max ansel_bg-black-opacity-60">
        <div
            className="ansel_absolute ansel_top-0 ansel_left-0 ansel_w-full ansel_h-full ansel_flex ansel_items-center ansel_justify-center">
            <div>
                <div className="ansel_relative">
                    <div>
                        <ReactCrop
                            crop={crop}
                            onChange={(_, c) => setCrop(c)}
                            aspect={fieldData.fieldSettings.ratioAsNumber}
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
    </div>;
};

export default CropImage;
