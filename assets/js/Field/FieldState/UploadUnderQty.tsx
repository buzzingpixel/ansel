import { ExclamationCircleIcon } from '@heroicons/react/solid';
import * as React from 'react';
import { useImages } from './Images/ImagesContext';
import { useFieldSettings } from '../FieldSettings/FieldSettingsContext';
import { useTranslations } from '../Translations/TranslationsContext';

const UploadUnderQty = () => {
    const { images } = useImages();
    const { minQty } = useFieldSettings();
    const { fieldUnderLimit } = useTranslations();

    if (minQty < 1 || images.length >= minQty) {
        return <></>;
    }

    return <div
        className="ansel_bg-yellow-100 ansel_px-4 ansel_py-1.5 ansel_border-0 ansel_border-b ansel_border-yellow-200 ansel_border-solid"
    >
        <div className="ansel_flex">
            <div className="ansel_flex-shrink-0 ansel_-mb-2">
                <ExclamationCircleIcon
                    className="ansel_h-5 ansel_w-5 ansel_text-yellow-600"
                    aria-hidden="true"
                />
            </div>
            <div className="ansel_ml-3">
                <div className="ansel_text-sm ansel_font-medium ansel_text-gray-600">
                    {fieldUnderLimit}
                </div>
            </div>
        </div>
    </div>;
};

export default UploadUnderQty;
