import ObjectOfStringsType from './ObjectOfStringsType';
import ImageType from './ImageType';

interface FieldStateType {
    processes: number;
    errorMessages: ObjectOfStringsType;
    images: Array<ImageType>;
    delete: Array<string>;
}

export default FieldStateType;
