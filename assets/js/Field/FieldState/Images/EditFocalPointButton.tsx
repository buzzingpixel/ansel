import { IconContext } from 'react-icons';
import { CgEditBlackPoint } from 'react-icons/cg';
import * as React from 'react';

const EditFocalPointButton = () => <div className="ansel_-ml-px ansel_w-0 ansel_flex-1 ansel_flex ansel_border-0 ansel_border-l ansel_border-gray-200 ansel_border-solid">
    <a
        href="#"
        className="ansel_relative ansel_w-0 ansel_flex-1 ansel_inline-flex ansel_items-center ansel_justify-center ansel_py-4 ansel_text-sm ansel_text-gray-700 ansel_font-medium ansel_border ansel_border-transparent hover:ansel_text-gray-500 hover:ansel_bg-gray-200"
    >
        <IconContext.Provider value={{ color: '#525252' }}>
            <CgEditBlackPoint className="ansel_w-5 ansel_h-5 ansel_text-gray-400" aria-hidden="true" />
            <span className="ansel_sr-only">Edit Focal Point</span>
        </IconContext.Provider>
    </a>
</div>;

export default EditFocalPointButton;
