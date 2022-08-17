import {
    createContext, Dispatch, SetStateAction, useContext, useMemo, useState,
} from 'react';
import * as React from 'react';

interface RenderImagesFieldsContext {
    activeFieldImageId: string|null,
    setActiveFieldImageId: Dispatch<SetStateAction<string|null>>,
}

const RenderImagesFieldsContext = createContext<RenderImagesFieldsContext>(
    null,
);

const useRenderImagesFieldsContext = () => {
    const context = useContext(RenderImagesFieldsContext);

    if (!context) {
        throw new Error(
            'useRenderImagesFieldsContext must be used within a RenderImagesFieldsProvider',
        );
    }

    return context;
};

const RenderImagesFieldsProvider = ({
    children,
}: {
    children?:
        | React.ReactChild
        | React.ReactChild[],
}) => {
    const [
        activeFieldImageId,
        setActiveFieldImageId,
    ] = useState<string|null>(null);

    const value = useMemo(
        () => ({
            activeFieldImageId,
            setActiveFieldImageId,
        }),
        [activeFieldImageId],
    );

    return <RenderImagesFieldsContext.Provider
        value={value}
        children={children}
    />;
};

export { RenderImagesFieldsProvider, useRenderImagesFieldsContext };
