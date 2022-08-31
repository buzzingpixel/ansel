import * as React from 'react';
import { IconContext } from 'react-icons';
import { MdDelete } from 'react-icons/md';
import { useImages } from './ImagesContext';
import { useRenderImageContext } from './RenderImageContext';

const DeleteButton = () => {
    const { image } = useRenderImageContext();

    const { removeImage, addDeletedId } = useImages();

    const remove = (event: React.MouseEvent<HTMLAnchorElement>) => {
        event.preventDefault();

        const id = String(image.id).toString();

        removeImage(id);

        addDeletedId(id);
    };

    return (
        <div className="ansel_-ml-px ansel_w-0 ansel_flex-1 ansel_flex ansel_border-0 ansel_border-l ansel_border-gray-200 ansel_border-solid">
            <a
                onClick={remove}
                href="#0"
                className="ansel_relative ansel_w-0 ansel_flex-1 ansel_inline-flex ansel_items-center ansel_justify-center ansel_py-4 ansel_text-sm ansel_text-gray-700 ansel_font-medium ansel_border ansel_border-transparent ansel_rounded-br-lg hover:ansel_text-gray-500 hover:ansel_bg-gray-200"
            >
                <IconContext.Provider value={{ color: '#525252' }}>
                    <MdDelete className="ansel_w-5 ansel_h-5 ansel_text-gray-400" aria-hidden="true" />
                    <span className="ansel_sr-only">Remove Image</span>
                </IconContext.Provider>
            </a>
        </div>
    );
};

export default DeleteButton;
