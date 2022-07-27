import { FileRejection, useDropzone } from 'react-dropzone';
import { useCallback } from 'react';
import { useErrorMessages } from '../FieldState/ErrorMessages/ErrorMessagesContext';

const useAnselDropZone = () => {
    const { errorMessages, addErrorMessage } = useErrorMessages();

    const onDropAccepted = useCallback(
        (files: Array<File>) => {
            // eslint-disable-next-line no-console
            console.log(files);
        },
        [],
    );

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

    const {
        getRootProps,
        getInputProps,
        open,
        isDragActive,
    } = useDropzone(
        {
            onDropAccepted,
            onDropRejected,
            noClick: true,
            noKeyboard: true,
            accept: 'image/jpeg, image/png, image/gif',
        },
    );

    return {
        getDropZoneRootProps: getRootProps,
        getDropZoneInputProps: getInputProps,
        openDropZoneDeviceDialog: open,
        isDropZoneDragActive: isDragActive,
    };
};

export default useAnselDropZone;
