import { v4 as uuid } from 'uuid';
import FieldStateType from '../../Types/FieldStateType';

const UploadErrorHandler = (
    setFieldState: CallableFunction,
    message?: string,
) => {
    const id = uuid();

    message = message || 'There was an error uploading your image';

    setFieldState((prevState: FieldStateType) => {
        prevState.errorMessages[id] = message;

        return { ...prevState };
    });

    setTimeout(() => {
        setFieldState((prevState) => {
            delete prevState.errorMessages[id];

            return { ...prevState };
        });
    }, 10000);
};

export default UploadErrorHandler;
