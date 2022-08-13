import * as React from 'react';
import { useRef, useState } from 'react';
import { useRenderImageContext } from './RenderImageContext';
import useWindowResize from '../../../Hooks/useWindowResize';

const RenderThumbnail = () => {
    // TODO: lock if source file is missing

    const { image } = useRenderImageContext();

    const containerRef = useRef<HTMLDivElement|null>();

    const { pixelCropState } = useRenderImageContext();

    const [containerWidth, setContainerWidth] = useState<number>(
        192,
    );

    useWindowResize(() => {
        if (!containerRef || !containerRef.current) {
            return;
        }

        setContainerWidth(containerRef.current.clientWidth);
    });

    if (pixelCropState === null) {
        return (
            <div className="ansel_w-48 ansel_flex-shrink-0 ansel_mx-auto ansel_overflow-hidden ansel_relative"
                 style={{
                     height: '90px',
                     position: 'relative',
                 }}
            />
        );
    }

    const ratio = pixelCropState.width / pixelCropState.height;

    const containerHeight = Math.round(containerWidth / ratio);

    const widthPercent = containerWidth / pixelCropState.width;
    const heightPercent = containerHeight / pixelCropState.height;
    const thumbWidth = Math.round(widthPercent * pixelCropState.imageWidth);
    const thumbHeight = Math.round(heightPercent * pixelCropState.imageHeight);
    const thumbX = Math.round(widthPercent * pixelCropState.x) * -1;
    const thumbY = Math.round(heightPercent * pixelCropState.y) * -1;

    return (
        <div
            ref={containerRef}
            className="ansel_w-48 ansel_flex-shrink-0 ansel_mx-auto ansel_overflow-hidden ansel_relative"
            style={{
                height: `${containerHeight}px`,
                position: 'relative',
            }}
        >
            <img
                src={image.imageUrl}
                style={{
                    position: 'absolute',
                    width: `${thumbWidth}px`,
                    height: `${thumbHeight}px`,
                    left: `${thumbX}px`,
                    top: `${thumbY}px`,
                    maxWidth: 'none',
                }}
                alt=""
            />
        </div>
    );
};

export default RenderThumbnail;
