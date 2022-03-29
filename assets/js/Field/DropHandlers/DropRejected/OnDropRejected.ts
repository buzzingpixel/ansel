import { FileRejection } from 'react-dropzone';
import RejectedItemHandler from './RejectedItemHandler';

const OnDropRejected = (
    rejected: Array<FileRejection>,
    setFieldState: CallableFunction,
) => {
    rejected.forEach((rejectedItem) => {
        RejectedItemHandler(rejectedItem, setFieldState);
    });
};

export default OnDropRejected;
