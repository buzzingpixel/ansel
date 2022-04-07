import EeFileType from './EeFileType';
import ImageType from '../Types/ImageType';
import FieldStateType from '../Types/FieldStateType';
import UploadErrorHandler from '../DropHandlers/DropAccepted/UploadErrorHandler';
import UrlIsImage from '../../Utility/UrlIsImage';

const SelectedFileHandlerEe = (
    file: EeFileType,
    setFieldState: CallableFunction,
) => {
    // Check if ExpressionEngine says this file is invalid for us
    if (!file.isImage || file.isSVG) {
        UploadErrorHandler(
            setFieldState,
            // TODO: Get from translations
            'The selected file is not a usable image',
        );

        return;
    }

    // Do our own check
    UrlIsImage(file.path)
        .then(() => {
            // TODO: Make sure image meets requirements
            const image = {
                imageUrl: file.path,
            } as ImageType;

            setFieldState((prevState: FieldStateType) => {
                prevState.images = [
                    ...prevState.images,
                    image,
                ];

                return { ...prevState };
            });
        })
        .catch(() => {
            UploadErrorHandler(
                setFieldState,
                // TODO: Get from translations
                'The selected file is not a usable image',
            );
        });
};

export default SelectedFileHandlerEe;
