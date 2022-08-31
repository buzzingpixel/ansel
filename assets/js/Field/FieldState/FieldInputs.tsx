import * as React from 'react';
import { useInputPlaceholder } from '../InputPlaceholder/InputPlaceholderContext';
import { useImages } from './Images/ImagesContext';

const FieldInputs = () => {
    const { images, deletedIds } = useImages();

    const inputPlaceholder = useInputPlaceholder();

    const inputBaseName = inputPlaceholder.name;

    return <>
        {images.map((image) => {
            const localImage = { ...image };

            delete localImage.imageUrl;

            return <input
                key={`image-input-${image.id}`}
                type="hidden"
                name={`${inputBaseName}[images][${image.id}]`}
                value={JSON.stringify(localImage)}
            />;
        })}
        {deletedIds.length > 0 && <input
            type="hidden"
            name={`${inputBaseName}[delete_images]`}
            value={JSON.stringify(deletedIds)}
        />}
    </>;
};

export default FieldInputs;
