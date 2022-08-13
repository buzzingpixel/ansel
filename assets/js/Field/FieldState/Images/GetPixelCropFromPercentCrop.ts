import { PercentCrop } from 'react-image-crop';
import PixelCropPlusImageDimensionsContract from './PixelCropPlusImageDimensionsContract';

const GetPixelCropFromPercentCrop = async (
    imageUrl: string,
    crop: PercentCrop,
) => new Promise<PixelCropPlusImageDimensionsContract>(
    (resolve, reject) => {
        const imageEl = new Image();

        imageEl.onload = () => {
            const xDecimal = crop.x / 100;
            const yDecimal = crop.y / 100;
            const widthDecimal = crop.width / 100;
            const heightDecimal = crop.height / 100;

            resolve({
                unit: 'px',
                x: Math.round(xDecimal * imageEl.naturalWidth),
                y: Math.round(yDecimal * imageEl.naturalHeight),
                width: Math.round(widthDecimal * imageEl.naturalWidth),
                height: Math.round(heightDecimal * imageEl.naturalHeight),
                imageWidth: imageEl.naturalWidth,
                imageHeight: imageEl.naturalHeight,
            });
        };

        imageEl.onerror = reject;

        imageEl.src = imageUrl;
    },
);

export default GetPixelCropFromPercentCrop;
