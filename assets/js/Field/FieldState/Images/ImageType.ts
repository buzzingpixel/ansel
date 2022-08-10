import { ItemInterface } from 'react-sortablejs';
import ImageUploadType from './ImageUploadType';
import ImageManipulationCacheType from './ImageManipulationCacheType';

interface ImageType extends ItemInterface {
    imageUpload?: ImageUploadType;
    imageManipulationCache?: ImageManipulationCacheType;
    imageUrl: string;
    sourceImageId?: string;
    x: number;
    y: number;
    width: number;
    height: number;
}

export default ImageType;
