import * as React from 'react';
import { useRenderImageContext } from './RenderImageContext';

const RenderThumbnail = () => {
    const { image } = useRenderImageContext();

    return <div
        className="ansel_w-48 ansel_flex-shrink-0 ansel_mx-auto ansel_overflow-hidden ansel_relative"
        style={{
            height: '90px',
            // height: `${containerHeight}px`,
            position: 'relative',
        }}
    >
        <img
            src={image.imageUrl}
            style={{
                position: 'absolute',
                width: '90px',
                height: '90px',
                // width: `${thumbWidth}px`,
                // height: `${thumbHeight}px`,
                // left: `${thumbX}px`,
                // top: `${thumbY}px`,
                maxWidth: 'none',
            }}
            alt=""
        />
    </div>;
};

export default RenderThumbnail;
