import { PixelCrop } from 'react-image-crop';

interface PixelCropPlusImageDimensions extends PixelCrop {
    imageWidth: number;
    imageHeight: number;
}

export default PixelCropPlusImageDimensions;
