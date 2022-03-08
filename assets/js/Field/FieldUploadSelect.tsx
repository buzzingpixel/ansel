import * as React from 'react';
import { ImPlus } from 'react-icons/im';
import { IconContext } from 'react-icons';

const FieldUploadSelect = () => (
    <>
        <div className="ansel_text-gray-700 ansel_italic ansel_text-center ansel_pb-4">
            Drag images here to upload
        </div>
        <div className="ansel_text-gray-700 ansel_italic ansel_text-center">
            <a
                href="#"
                className="ansel_border ansel_border-dashed ansel_border-gray-300 ansel_inline-block ansel_mx-auto ansel_py-0.5 ansel_px-1.5 ansel_not-italic ansel_text-gray-700 hover:ansel_text-gray-700 hover:ansel_bg-gray-200"
            >
                <IconContext.Provider value={{ color: '#525252' }}>
                    <span
                        className="ansel_inline-block ansel_mx-auto ansel_align-middle ansel_pr-1"
                        style={{ height: '18px' }}
                    >
                        <ImPlus />
                    </span>
                    <span className="ansel_inline-block ansel_mx-auto ansel_align-middle">
                        Select existing image
                    </span>
                </IconContext.Provider>
            </a>
        </div>
    </>
);

export default FieldUploadSelect;
