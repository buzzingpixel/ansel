import ImageUploadType from './ImageUploadType';

interface ImageType {
    uid: string;
    imageUpload?: ImageUploadType;
    imageUrl: string;
    x: number;
    y: number;
    width: number;
    height: number;
}

export default ImageType;
