import { ItemInterface } from 'react-sortablejs';
import ImageUploadType from './ImageUploadType';
import ImageManipulationCacheType from './ImageManipulationCacheType';
import ImageFieldType from './ImageFieldType';

interface ImageType extends ItemInterface {
    imageUpload?: ImageUploadType;
    imageManipulationCache?: ImageManipulationCacheType;
    imageUrl: string;
    sourceImageId?: string;
    x: number;
    y: number;
    width: number;
    height: number;
    focalX: number;
    focalY: number;
    fieldData?: { [key: string]: ImageFieldType };
}

export default ImageType;
