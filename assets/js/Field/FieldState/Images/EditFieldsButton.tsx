import { IconContext } from 'react-icons';
import { FiEdit } from 'react-icons/fi';
import * as React from 'react';
import { useRenderImageContext } from './RenderImageContext';
import { useRenderImagesFieldsContext } from './RenderImagesFieldsContext';
import { useCustomFields } from '../../CustomFields/CustomFieldsContext';

const EditFieldsButton = () => {
    const { image } = useRenderImageContext();
    const { setActiveFieldImageId } = useRenderImagesFieldsContext();
    const customFields = useCustomFields();

    if (customFields.length < 1) {
        return <></>;
    }

    return <div className="ansel_-ml-px ansel_w-0 ansel_flex-1 ansel_flex ansel_border-0 ansel_border-l ansel_border-gray-200 ansel_border-solid">
        <a
            onClick={(event: React.MouseEvent) => {
                event.preventDefault();

                setActiveFieldImageId(String(image.id).toString());
            }}
            href="#0"
            className="ansel_relative ansel_-mr-px w-0 ansel_flex-1 ansel_inline-flex ansel_items-center ansel_justify-center ansel_py-4 ansel_text-sm ansel_text-gray-700 ansel_font-medium border ansel_border-transparent hover:ansel_bg-gray-200"
        >
            <IconContext.Provider value={{ color: '#525252' }}>
                <FiEdit className="ansel_w-5 ansel_h-5 ansel_text-gray-400" aria-hidden="true"/>
                <span className="ansel_sr-only">Edit Fields</span>
            </IconContext.Provider>
        </a>
    </div>;
};

export default EditFieldsButton;
