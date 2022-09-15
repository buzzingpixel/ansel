import * as React from 'react';
import { InformationCircleIcon } from '@heroicons/react/solid';
import { useTranslations } from '../Translations/TranslationsContext';
import { useImages } from './Images/ImagesContext';
import { useFieldSettings } from '../FieldSettings/FieldSettingsContext';

const UploadOverQty = () => {
    const { images } = useImages();
    const { maxQty } = useFieldSettings();
    const { fieldOverLimit } = useTranslations();

    if (maxQty < 1 || images.length <= maxQty) {
        return <></>;
    }

    return <div
        className="ansel_bg-blue-50 ansel_px-4 ansel_py-1.5 ansel_border-0 ansel_border-b ansel_border-blue-100 ansel_border-solid"
    >
        <div className="ansel_flex">
            <div className="ansel_flex-shrink-0 ansel_-mb-2">
                <InformationCircleIcon
                    className="ansel_h-5 ansel_w-5 ansel_text-blue-400"
                    aria-hidden="true"
                />
            </div>
            <div className="ansel_ml-3">
                <div className="ansel_text-sm ansel_font-medium ansel_text-gray-600">
                    {fieldOverLimit}
                </div>
            </div>
        </div>
    </div>;
};

export default UploadOverQty;
