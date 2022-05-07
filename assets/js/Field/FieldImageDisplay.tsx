import * as React from 'react';
import { useEffect, useRef, useState } from 'react';
import ImageType from './Types/ImageType';
import PixelCropPlusImageDimensions from './Types/PixelCropPlusImageDimensions';

const FieldImageDisplay = ({
    crop,
    image,
}: {
    crop: PixelCropPlusImageDimensions|null,
    image: ImageType,
}) => {
    const containerRef = useRef<HTMLDivElement>();

    const [containerWidth, setContainerWidth] = useState<number>(
        192,
    );

    useEffect(() => {
        const handler = () => {
            setContainerWidth(containerRef.current.clientWidth);
        };

        window.addEventListener('resize', handler);

        return () => window.removeEventListener('resize', handler);
    }, [
        containerWidth,
    ]);

    if (crop === null) {
        return <></>;
    }

    const ratio = crop.width / crop.height;

    const containerHeight = Math.round(containerWidth / ratio);

    const widthPercent = containerWidth / crop.width;
    const heightPercent = containerHeight / crop.height;
    const thumbWidth = Math.round(widthPercent * crop.imageWidth);
    const thumbHeight = Math.round(heightPercent * crop.imageHeight);
    const thumbX = Math.round(widthPercent * crop.x) * -1;
    const thumbY = Math.round(heightPercent * crop.y) * -1;

    // TODO: lock if source file is missing

    return <div
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
    </div>;
};

export default FieldImageDisplay;
