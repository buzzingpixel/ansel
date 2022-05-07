import ReactCrop, { PercentCrop } from 'react-image-crop';
import { MdClose } from 'react-icons/md';
import { BsCheck } from 'react-icons/bs';
import * as React from 'react';
import { useEffect } from 'react';
import ImageType from '../Types/ImageType';
import GetPixelCropFromPercentCrop from '../Utility/GetPixelCropFromPercentCrop';

const CropImage = ({
    crop,
    image,
    setCrop,
    acceptedCrop,
    setCropIsOpen,
    setAcceptedCrop,
    setPixelCropState,
}: {
    crop: PercentCrop,
    image: ImageType,
    setCrop: CallableFunction,
    acceptedCrop: PercentCrop,
    setCropIsOpen: CallableFunction,
    setAcceptedCrop: CallableFunction,
    setPixelCropState: CallableFunction,
}) => {
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
                        >
                            <img src={image.imageUrl} alt=""/>
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
