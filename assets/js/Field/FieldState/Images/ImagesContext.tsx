import * as React from 'react';
import {
    createContext, Dispatch, SetStateAction, useContext, useMemo, useState,
} from 'react';
import ImageType from './ImageType';

interface ImagesContextType {
    hasImages: boolean,
    images: Array<ImageType>,
    addImage: (image: ImageType) => void,
    setImages: Dispatch<SetStateAction<Array<ImageType>>>,
    removeImage: (index: number) => void,
}

const ImagesContext = createContext<ImagesContextType>(null);

const useImages = () => {
    const context = useContext(ImagesContext);

    if (!context) {
        throw new Error(
            'useImages must be used within a ImagesProvider',
        );
    }

    return context;
};

const ImagesProvider = (props: {
    images?: Array<ImageType>,
    children?:
        | React.ReactChild
        | React.ReactChild[],
}) => {
    let hasImages = false;

    const initialImages = props.images || [];

    const [images, setImages] = useState<Array<ImageType>>(initialImages);

    const addImage = (image: ImageType) => {
        setImages((prevState) => {
            prevState = [
                ...prevState,
                image,
            ];

            return { ...prevState };
        });
    };

    const removeImage = (index: number) => {
        setImages((prevState) => {
            prevState.splice(index, 1);

            return { ...prevState };
        });
    };

    if (images.length > 0) {
        hasImages = true;
    }

    const value = useMemo(
        () => ({
            images,
            addImage,
            hasImages,
            setImages,
            removeImage,
        }),
        [images],
    );

    return <ImagesContext.Provider
        value={value}
        children={props.children}
    />;
};

export { ImagesProvider, useImages };
