import { FileRejection } from 'react-dropzone';
import { useCallback } from 'react';
import { useErrorMessages } from '../FieldState/ErrorMessages/ErrorMessagesContext';

const useOnDropRejected = () => {
    const { errorMessages, addErrorMessage } = useErrorMessages();

    const onDropRejected = useCallback(
        (rejected: Array<FileRejection>) => {
            rejected.forEach((rejectedItem) => {
                rejectedItem.errors.forEach((error) => {
                    addErrorMessage(
                        `${rejectedItem.file.name} ${error.message}`,
                    );
                });
            });
        },
        [errorMessages],
    );

    return ({
        onDropRejected,
    });
};

export default useOnDropRejected;
