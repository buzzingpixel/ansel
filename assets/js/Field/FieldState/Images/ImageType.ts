import { ItemInterface } from 'react-sortablejs';
import ImageUploadType from './ImageUploadType';
import ImageManipulationCacheType from './ImageManipulationCacheType';
import ImageFieldType from './ImageFieldType';

interface ImageType extends ItemInterface {
    imageUrl: string;
    imageFileName: string;
    sourceImageId?: string;
    x: number;
    y: number;
    width: number;
    height: number;
    focalX: number;
    focalY: number;
    fieldData?: { [key: string]: ImageFieldType };
    imageUpload?: ImageUploadType;
    imageManipulationCache?: ImageManipulationCacheType;
}

export default ImageType;
