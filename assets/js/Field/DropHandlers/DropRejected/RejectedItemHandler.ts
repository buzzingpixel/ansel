import { FileRejection } from 'react-dropzone';
import ErrorHandler from './ErrorHandler';

const RejectedItemHandler = (
    rejectedItem: FileRejection,
    setErrorMessages: CallableFunction,
) => {
    rejectedItem.errors.forEach((error) => {
        ErrorHandler(
            rejectedItem.file.name,
            error.message,
            setErrorMessages,
        );
    });
};

export default RejectedItemHandler;
