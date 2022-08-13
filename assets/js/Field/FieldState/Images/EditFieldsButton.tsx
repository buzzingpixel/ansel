import { IconContext } from 'react-icons';
import { FiEdit } from 'react-icons/fi';
import * as React from 'react';

const EditFieldsButton = () => <div className="ansel_w-0 ansel_flex-1 ansel_flex">
    <a
        href="#"
        className="ansel_relative ansel_-mr-px w-0 ansel_flex-1 ansel_inline-flex ansel_items-center ansel_justify-center ansel_py-4 ansel_text-sm ansel_text-gray-700 ansel_font-medium border ansel_border-transparent ansel_rounded-bl-lg hover:ansel_bg-gray-200"
    >
        <IconContext.Provider value={{ color: '#525252' }}>
            <FiEdit className="ansel_w-5 ansel_h-5 ansel_text-gray-400" aria-hidden="true" />
            <span className="ansel_sr-only">Edit Fields</span>
        </IconContext.Provider>
    </a>
</div>;

export default EditFieldsButton;
