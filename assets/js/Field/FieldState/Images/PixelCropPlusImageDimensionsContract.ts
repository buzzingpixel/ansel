import { PixelCrop } from 'react-image-crop';

interface PixelCropPlusImageDimensionsContract extends PixelCrop {
    imageWidth: number;
    imageHeight: number;
}

export default PixelCropPlusImageDimensionsContract;
