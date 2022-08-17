import * as React from 'react';
import { useRenderImagesFieldsContext } from './RenderImagesFieldsContext';

const RenderImageFields = () => {
    const { activeFieldImageId } = useRenderImagesFieldsContext();

    if (!activeFieldImageId) {
        return <></>;
    }

    return <>TODO: Render fields</>;
};

export default RenderImageFields;
