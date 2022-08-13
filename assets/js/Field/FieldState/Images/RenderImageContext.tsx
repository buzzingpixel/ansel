import {
    createContext, Dispatch, SetStateAction, useContext, useMemo, useState,
} from 'react';
import * as React from 'react';
import ImageType from './ImageType';

interface RenderImageContextType {
    image: ImageType,
    cropIsOpen: boolean,
    setCropIsOpen: Dispatch<SetStateAction<boolean>>,
}

const RenderImageContext = createContext<RenderImageContextType>(
    null,
);

const useRenderImageContext = () => {
    const context = useContext(RenderImageContext);

    if (!context) {
        throw new Error(
            'useRenderImageContext must be used within a RenderImageProvider',
        );
    }

    return context;
};

const RenderImageProvider = ({
    image,
    children,
}: {
    image: ImageType,
    children?:
        | React.ReactChild
        | React.ReactChild[],
}) => {
    const [cropIsOpen, setCropIsOpen] = useState<boolean>(false);

    const value = useMemo(
        () => ({
            image,
            cropIsOpen,
            setCropIsOpen,
        }),
        [image, cropIsOpen],
    );

    return <RenderImageContext.Provider
        value={value}
        children={children}
    />;
};

export { RenderImageProvider, useRenderImageContext };
