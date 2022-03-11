import { v4 as uuid } from 'uuid';

const ErrorHandler = (
    fileName: string,
    errorMessage: string,
    setErrorMessages: CallableFunction,
) => {
    const id = uuid();

    setErrorMessages((prevState) => ({
        ...prevState,
        [id]: `${fileName}: ${errorMessage}`,
    }));

    setTimeout(() => {
        setErrorMessages((prevState) => {
            delete prevState[id];
            return { ...prevState };
        });
    }, 10000);
};

export default ErrorHandler;
