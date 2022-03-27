import { v4 as uuid } from 'uuid';

const UploadErrorHandler = (
    setErrorMessages: CallableFunction,
    message?: string,
) => {
    const id = uuid();

    message = message || 'There was an error uploading your image';

    setErrorMessages((prevState) => ({
        ...prevState,
        [id]: message,
    }));

    setTimeout(() => {
        setErrorMessages((prevState) => {
            delete prevState[id];
            return { ...prevState };
        });
    }, 10000);
};

export default UploadErrorHandler;
