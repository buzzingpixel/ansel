import FileHandler from './FileHandler';
import FieldDataType from '../../Types/FieldDataType';

const OnDropAccepted = (
    files: Array<File>,
    fieldData: FieldDataType,
    setFieldState: CallableFunction,
) => {
    files.forEach((file) => {
        FileHandler(
            file,
            fieldData,
            setFieldState,
        );
    });
};

export default OnDropAccepted;
