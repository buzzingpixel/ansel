import { PercentCrop } from 'react-image-crop';
import ImageType from '../Types/ImageType';
import PixelCropPlusImageDimensions from '../Types/PixelCropPlusImageDimensions';

const GetPixelCropFromPercentCrop = async (
    image: ImageType,
    crop: PercentCrop,
) => new Promise<PixelCropPlusImageDimensions>((resolve, reject) => {
    const imageElement = new Image();

    imageElement.src = image.imageUrl;

    imageElement.onload = () => {
        const xDecimal = crop.x / 100;
        const yDecimal = crop.y / 100;
        const widthDecimal = crop.width / 100;
        const heightDecimal = crop.height / 100;

        resolve({
            unit: 'px',
            x: Math.round(xDecimal * imageElement.naturalWidth),
            y: Math.round(yDecimal * imageElement.naturalHeight),
            width: Math.round(widthDecimal * imageElement.naturalWidth),
            height: Math.round(heightDecimal * imageElement.naturalHeight),
            imageWidth: imageElement.naturalWidth,
            imageHeight: imageElement.naturalHeight,
        });
    };

    imageElement.onerror = reject;

    imageElement.src = image.imageUrl;
});

export default GetPixelCropFromPercentCrop;
