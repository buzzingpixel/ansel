import { FileRejection } from 'react-dropzone';
import ErrorHandler from './ErrorHandler';

const RejectedItemHandler = (
    rejectedItem: FileRejection,
    setFieldState: CallableFunction,
) => {
    rejectedItem.errors.forEach((error) => {
        ErrorHandler(
            rejectedItem.file.name,
            error.message,
            setFieldState,
        );
    });
};

export default RejectedItemHandler;
