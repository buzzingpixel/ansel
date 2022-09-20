import { useDropzone } from 'react-dropzone';
import useOnDropAccepted from './OnDropAccepted';
import useOnDropRejected from './OnDropRejected';

const useAnselDropZone = () => {
    const { onDropAccepted } = useOnDropAccepted();
    const { onDropRejected } = useOnDropRejected();

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
            accept: {
                'image/jpeg': ['.jpg', '.jpeg'],
                'image/png': ['.png'],
                'image/gif': ['.gif'],
            },
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
