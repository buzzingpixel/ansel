import * as React from 'react';
import { useState } from 'react';
import { FocalPoint, useRenderImageContext } from './RenderImageContext';
import AnselPortal from '../../Utility/AnselPortal';
import useWindowResize from '../../../Hooks/useWindowResize';
import useContainerMouseCoords from '../../../Hooks/useContainerMouseCoords';

const RenderEditFocalPointInner = () => {
    const windowDimensions = useWindowResize();

    const { coords, handleMouseMove } = useContainerMouseCoords({
        x: 50,
        y: 50,
    });

    const { image, setFocalPoint, setFocalPointIsOpen } = useRenderImageContext();

    const [localFocal, setLocalFocal] = useState<FocalPoint>({
        x: image.focalX,
        y: image.focalY,
    });

    const { pixelCropState } = useRenderImageContext();

    const accept = (event: Event|React.MouseEvent) => {
        event.preventDefault();

        setFocalPoint({ ...localFocal });

        setFocalPointIsOpen(false);
    };

    const cancel = (event: Event|React.MouseEvent) => {
        event.preventDefault();

        setLocalFocal(() => ({
            x: image.focalX,
            y: image.focalY,
        }));

        setFocalPointIsOpen(false);
    };

    let finalWidth = image.width;

    const adjustedWindowWidth = windowDimensions.width - 40;

    let finalHeight = image.height;

    const adjustedWindowHeight = windowDimensions.height - 80;

    let percentAdjuster = 0;

    if (adjustedWindowWidth < finalWidth) {
        percentAdjuster = adjustedWindowWidth / image.width;

        finalWidth = adjustedWindowWidth;

        finalHeight = Math.round(image.height * percentAdjuster);
    }

    if (adjustedWindowHeight < finalHeight) {
        percentAdjuster = adjustedWindowHeight / image.height;

        finalHeight = adjustedWindowHeight;

        finalWidth = Math.round(image.width * percentAdjuster);
    }

    const imgWidthPercent = finalWidth / pixelCropState.width;
    const imgHeightPercent = finalHeight / pixelCropState.height;
    const imgWidth = Math.round(imgWidthPercent * pixelCropState.imageWidth);
    const imgHeight = Math.round(imgHeightPercent * pixelCropState.imageHeight);
    const imgX = Math.round(imgWidthPercent * pixelCropState.x) * -1;
    const imgY = Math.round(imgHeightPercent * pixelCropState.y) * -1;

    return (
        <AnselPortal accept={accept} cancel={cancel}>
            <div
                onMouseMove={handleMouseMove}
                onClick={() => {
                    setLocalFocal(coords);
                }}
                className='ansel_mb-2 ansel_overflow-hidden ansel_cursor-crosshair'
                style={{
                    backgroundColor: '#d5d5d5',
                    width: finalWidth,
                    height: finalHeight,
                    position: 'relative',
                }}
            >
                <div
                    className="ansel_absolute ansel_rounded-full ansel_border-white ansel_border-solid ansel_opacity-75 ansel_bg-black ansel_z-50 ansel_border-2"
                    style={{
                        width: '18px',
                        height: '18px',
                        left: `${localFocal.x}%`,
                        top: `${localFocal.y}%`,
                        transform: 'translate(-50%, -50%)',
                    }}
                >
                    <div
                        className="ansel_absolute ansel_rounded-full ansel_bg-white"
                        style={{
                            width: '5px',
                            height: '5px',
                            left: '50%',
                            top: '50%',
                            transform: 'translate(-50%, -50%)',
                        }}
                    ></div>
                </div>
                <img
                    src={image.imageUrl}
                    style={{
                        position: 'absolute',
                        width: `${imgWidth}px`,
                        height: `${imgHeight}px`,
                        left: `${imgX}px`,
                        top: `${imgY}px`,
                        maxWidth: 'none',
                    }}
                    alt=''
                />
            </div>
        </AnselPortal>
    );
};

const RenderEditFocalPoint = () => {
    const { focalPointIsOpen } = useRenderImageContext();

    if (!focalPointIsOpen) {
        return <></>;
    }

    return <RenderEditFocalPointInner />;
};

export default RenderEditFocalPoint;
