import { PercentCrop } from 'react-image-crop';
import { IconContext } from 'react-icons';
import { FiEdit } from 'react-icons/fi';
import { CgEditBlackPoint } from 'react-icons/cg';
import * as React from 'react';
import { useState } from 'react';
import { SortableHandle } from 'react-sortable-hoc';
import DeleteButton from './InteractionHandlers/DeleteButton';
import ImageType from './Types/ImageType';
import EditImageButton from './InteractionHandlers/EditImageButton';
import CropImage from './InteractionHandlers/CropImage';
import FieldImageDisplay from './FieldImageDisplay';
import GetPixelCropFromPercentCrop from './Utility/GetPixelCropFromPercentCrop';
import PixelCropPlusImageDimensions from './Types/PixelCropPlusImageDimensions';

const SortHandle = SortableHandle(({ children }) => <>{children}</>);

const FieldImage = ({
    setFieldState,
    image,
    index,
}: {
    setFieldState: CallableFunction,
    image: ImageType,
    index: number,
}) => {
    const [cropIsOpen, setCropIsOpen] = useState<boolean>(false);

    const [crop, setCrop] = useState<PercentCrop>({
        unit: '%',
        x: 0,
        y: 0,
        width: 100,
        height: 100,
    });

    const [acceptedCrop, setAcceptedCrop] = useState<PercentCrop>(
        { ...crop },
    );

    const [pixelCropState, setPixelCropState] = useState<PixelCropPlusImageDimensions|null>(
        null,
    );

    if (pixelCropState === null) {
        GetPixelCropFromPercentCrop(image, acceptedCrop).then(
            (incomingCrop) => {
                setPixelCropState({ ...incomingCrop });
            },
        );
    }

    return <li
        key={index}
        className="ansel_col-span-1 ansel_flex ansel_flex-col ansel_text-center ansel_bg-white ansel_rounded-lg ansel_shadow ansel_divide-y ansel_divide-gray-200"
    >
        {cropIsOpen && <CropImage
            crop={crop}
            image={image}
            setCrop={setCrop}
            acceptedCrop={acceptedCrop}
            setCropIsOpen={setCropIsOpen}
            setAcceptedCrop={setAcceptedCrop}
            setPixelCropState={setPixelCropState}
        />}
        <SortHandle>
            <div className="ansel_flex-1 ansel_flex ansel_flex-col ansel_p-8 ansel_cursor-grab">
                <FieldImageDisplay crop={pixelCropState} image={image} />
            </div>
        </SortHandle>
        <div>
            <div className="ansel_-mt-px ansel_flex ansel_border-0 ansel_border-t ansel_border-gray-200 ansel_border-solid">
                <div className="ansel_w-0 ansel_flex-1 ansel_flex">
                    <a
                        href="#"
                        className="ansel_relative ansel_-mr-px w-0 ansel_flex-1 ansel_inline-flex ansel_items-center ansel_justify-center ansel_py-4 ansel_text-sm ansel_text-gray-700 ansel_font-medium border ansel_border-transparent ansel_rounded-bl-lg hover:ansel_bg-gray-200"
                    >
                        <IconContext.Provider value={{ color: '#525252' }}>
                            <FiEdit className="ansel_w-5 ansel_h-5 ansel_text-gray-400" aria-hidden="true" />
                            <span className="ansel_sr-only">Edit Fields</span>
                        </IconContext.Provider>
                    </a>
                </div>
                <EditImageButton setCropIsOpen={setCropIsOpen} />
                <div className="ansel_-ml-px ansel_w-0 ansel_flex-1 ansel_flex ansel_border-0 ansel_border-l ansel_border-gray-200 ansel_border-solid">
                    <a
                        href="#"
                        className="ansel_relative ansel_w-0 ansel_flex-1 ansel_inline-flex ansel_items-center ansel_justify-center ansel_py-4 ansel_text-sm ansel_text-gray-700 ansel_font-medium ansel_border ansel_border-transparent hover:ansel_text-gray-500 hover:ansel_bg-gray-200"
                    >
                        <IconContext.Provider value={{ color: '#525252' }}>
                            <CgEditBlackPoint className="ansel_w-5 ansel_h-5 ansel_text-gray-400" aria-hidden="true" />
                            <span className="ansel_sr-only">Edit Focal Point</span>
                        </IconContext.Provider>
                    </a>
                </div>
                <DeleteButton
                    index={index}
                    image={image}
                    setFieldState={setFieldState}
                />
            </div>
        </div>
    </li>;
};

export default FieldImage;
