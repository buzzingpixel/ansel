import * as React from 'react';
import { IconContext } from 'react-icons';
import { FiEdit } from 'react-icons/fi';
import { CgEditBlackPoint } from 'react-icons/cg';
import { ImCrop } from 'react-icons/im';
import { MdDelete } from 'react-icons/md';
import RenderThumbnail from './RenderThumbnail';
import ImageType from './ImageType';

const RenderImage = (
    {
        image,
        index,
    }: {
        image: ImageType,
        index: number,
    },
) => <li
    key={index}
    className="todo--ansel-field-working ansel_col-span-1 ansel_flex ansel_flex-col ansel_text-center ansel_bg-white ansel_rounded-lg ansel_shadow ansel_divide-y ansel_divide-gray-200"
>
    <div className="ansel-drag-handle ansel_flex-1 ansel_flex ansel_flex-col ansel_p-8 ansel_cursor-grab">
        <RenderThumbnail image={image} />
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
            <div
                className="ansel_-ml-px ansel_w-0 ansel_flex-1 ansel_flex ansel_border-0 ansel_border-l ansel_border-gray-200 ansel_border-solid">
                <a
                    href="#"
                    className="ansel_relative ansel_w-0 ansel_flex-1 ansel_inline-flex ansel_items-center ansel_justify-center ansel_py-4 ansel_text-sm ansel_text-gray-700 ansel_font-medium ansel_border ansel_border-transparent hover:ansel_text-gray-500 hover:ansel_bg-gray-200"
                >
                    <IconContext.Provider value={{ color: '#525252' }}>
                        <ImCrop className="ansel_w-5 ansel_h-5 ansel_text-gray-400" aria-hidden="true"/>
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
                    href="#0"
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
</li>;

export default RenderImage;
