import { IconContext } from 'react-icons';
import { ImCrop } from 'react-icons/im';
import * as React from 'react';
import { useRenderImageContext } from './RenderImageContext';
import { useTranslations } from '../../Translations/TranslationsContext';

const EditImageButton = () => {
    const { editImage } = useTranslations();
    const { toggleCropIsOpen } = useRenderImageContext();

    return <div
        className="ansel_w-0 ansel_flex-1 ansel_flex">
        <a
            onClick={(event: React.MouseEvent) => {
                event.preventDefault();

                toggleCropIsOpen();
            }}
            href="#0"
            className="ansel_relative ansel_w-0 ansel_flex-1 ansel_inline-flex ansel_items-center ansel_justify-center ansel_py-4 ansel_text-sm ansel_text-gray-700 ansel_font-medium ansel_border ansel_border-transparent hover:ansel_text-gray-500 hover:ansel_bg-gray-200 ansel_rounded-bl-lg"
            title={editImage}
        >
            <IconContext.Provider value={{ color: '#525252' }}>
                <ImCrop className="ansel_w-5 ansel_h-5 ansel_text-gray-400" aria-hidden="true"/>
                <span className="ansel_sr-only">{editImage}</span>
            </IconContext.Provider>
        </a>
    </div>;
};

export default EditImageButton;
