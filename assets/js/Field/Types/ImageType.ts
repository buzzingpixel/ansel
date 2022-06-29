import ImageUploadType from './ImageUploadType';
import ImageManipulationCacheType from './ImageManipulationCacheType';

interface ImageType {
    uid: string;
    imageUpload?: ImageUploadType;
    imageManipulationCache: ImageManipulationCacheType;
    imageUrl: string;
    sourceImageId?: string;
    x: number;
    y: number;
    width: number;
    height: number;
}

export default ImageType;
