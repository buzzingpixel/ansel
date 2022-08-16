import {
    createContext, Dispatch, SetStateAction, useContext, useMemo, useState,
} from 'react';
import * as React from 'react';

interface RenderImagesFieldsContext {
    activeFieldIndex: number|null,
    setActiveFieldIndex: Dispatch<SetStateAction<number|null>>,
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
        activeFieldIndex,
        setActiveFieldIndex,
    ] = useState<number|null>(null);

    const value = useMemo(
        () => ({
            activeFieldIndex,
            setActiveFieldIndex,
        }),
        [activeFieldIndex],
    );

    return <RenderImagesFieldsContext.Provider
        value={value}
        children={children}
    />;
};

export { RenderImagesFieldsProvider, useRenderImagesFieldsContext };
