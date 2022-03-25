import FileHandler from './FileHandler';
import FieldParametersType from '../../FieldParametersType';

const OnDropAccepted = (
    files: Array<File>,
    setImages: CallableFunction,
    parameters: FieldParametersType,
) => {
    files.forEach((file) => {
        FileHandler(file, setImages, parameters);
    });
};

export default OnDropAccepted;
