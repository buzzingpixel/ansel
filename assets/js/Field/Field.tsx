import * as React from 'react';
import { IconContext } from 'react-icons';
import { ImCrop } from 'react-icons/im';
import { FiEdit } from 'react-icons/fi';
import { CgEditBlackPoint } from 'react-icons/cg';
import { MdDelete } from 'react-icons/md';
import FieldSettingsType from './FieldSettingsType';
import CustomFieldType from './CustomFieldType';
import FieldUploadSelect from './FieldUploadSelect';

const images = [
    {
        imageUrl: 'https://images.unsplash.com/photo-1501532358732-8b50b34df1c4?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2940&q=80',
    },
    {
        imageUrl: 'https://images.unsplash.com/photo-1550479023-2a811e19dfd3?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2831&q=80',
    },
    {
        imageUrl: 'https://images.unsplash.com/photo-1504894577131-1ec09a4bc15b?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2050&q=80',
    },
    {
        imageUrl: 'https://images.unsplash.com/photo-1578374173696-f8a2d504b144?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2832&q=80',
    },
    {
        imageUrl: 'https://images.unsplash.com/photo-1599719500956-d158a26ab3ee?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2671&q=80',
    },
    {
        imageUrl: 'https://images.unsplash.com/photo-1587279484796-61a264afc18b?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2940&q=80',
    },
];

const Field = (
    {
        fieldSettings,
        customFields,
    }: {
        fieldSettings: FieldSettingsType,
        customFields: Array<CustomFieldType>,
    },
) => {
    // eslint-disable-next-line no-console
    console.log(fieldSettings, customFields);

    return (
        <div className="ansel_bg-gray-50 ansel_border ansel_border-gray-200 ansel_border-solid ansel_p-4">
            <div className="ansel_pb-6">
                <FieldUploadSelect />
            </div>
            <ul
                role="list"
                className="ansel_grid ansel_grid-cols-1 ansel_gap-6 md:ansel_grid-cols-2 2xl:ansel_grid-cols-3 3xl:ansel_grid-cols-4"
            >
                {images.map((image, index) => (
                    <li
                        key={index}
                        className="ansel_col-span-1 ansel_flex ansel_flex-col ansel_text-center ansel_bg-white ansel_rounded-lg ansel_shadow ansel_divide-y ansel_divide-gray-200"
                    >
                        <div className="ansel_flex-1 ansel_flex ansel_flex-col ansel_p-8">
                            <img
                                className="ansel_w-48 ansel_flex-shrink-0 ansel_mx-auto"
                                src={image.imageUrl}
                                alt=""
                            />
                        </div>
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
                                <div className="ansel_-ml-px ansel_w-0 ansel_flex-1 ansel_flex ansel_border-0 ansel_border-l ansel_border-gray-200 ansel_border-solid">
                                    <a
                                        href="#"
                                        className="ansel_relative ansel_w-0 ansel_flex-1 ansel_inline-flex ansel_items-center ansel_justify-center ansel_py-4 ansel_text-sm ansel_text-gray-700 ansel_font-medium ansel_border ansel_border-transparent hover:ansel_text-gray-500 hover:ansel_bg-gray-200"
                                    >
                                        <IconContext.Provider value={{ color: '#525252' }}>
                                            <ImCrop className="ansel_w-5 ansel_h-5 ansel_text-gray-400" aria-hidden="true" />
                                            <span className="ansel_sr-only">Edit Image</span>
                                        </IconContext.Provider>
                                    </a>
                                </div>
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
                                <div className="ansel_-ml-px ansel_w-0 ansel_flex-1 ansel_flex ansel_border-0 ansel_border-l ansel_border-gray-200 ansel_border-solid">
                                    <a
                                        href="#"
                                        className="ansel_relative ansel_w-0 ansel_flex-1 ansel_inline-flex ansel_items-center ansel_justify-center ansel_py-4 ansel_text-sm ansel_text-gray-700 ansel_font-medium ansel_border ansel_border-transparent ansel_rounded-br-lg hover:ansel_text-gray-500 hover:ansel_bg-gray-200"
                                    >
                                        <IconContext.Provider value={{ color: '#525252' }}>
                                            <MdDelete className="ansel_w-5 ansel_h-5 ansel_text-gray-400" aria-hidden="true" />
                                            <span className="ansel_sr-only">Remove Image</span>
                                        </IconContext.Provider>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                ))}
            </ul>
            {images.length > 4
                && <div className="ansel_pt-6">
                    <FieldUploadSelect />
                </div>
            }
        </div>
    );
};

export default Field;
