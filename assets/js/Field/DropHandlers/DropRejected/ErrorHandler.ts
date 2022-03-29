import { v4 as uuid } from 'uuid';
import FieldStateType from '../../Types/FieldStateType';

const ErrorHandler = (
    fileName: string,
    errorMessage: string,
    setFieldState: CallableFunction,
) => {
    const id = uuid();

    setFieldState((prevState: FieldStateType) => {
        prevState.errorMessages[id] = `${fileName}: ${errorMessage}`;

        return { ...prevState };
    });

    setTimeout(() => {
        setFieldState((prevState) => {
            delete prevState.errorMessages[id];

            return { ...prevState };
        });
    }, 10000);
};

export default ErrorHandler;
