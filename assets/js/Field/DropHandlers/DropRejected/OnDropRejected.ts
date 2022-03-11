import { FileRejection } from 'react-dropzone';
import RejectedItemHandler from './RejectedItemHandler';

const OnDropRejected = (
    rejected: Array<FileRejection>,
    setErrorMessages: CallableFunction,
) => {
    rejected.forEach((rejectedItem) => {
        RejectedItemHandler(rejectedItem, setErrorMessages);
    });
};

export default OnDropRejected;
