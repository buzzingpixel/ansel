import EeFileType from './EeFileType';
import ImageType from '../Types/ImageType';
import FieldStateType from '../Types/FieldStateType';
import UploadErrorHandler from '../DropHandlers/DropAccepted/UploadErrorHandler';
import UrlIsImage from '../../Utility/UrlIsImage';
import TranslationsType from '../Types/TranslationsType';

const SelectedFileHandlerEe = (
    file: EeFileType,
    setFieldState: CallableFunction,
    translations: TranslationsType,
) => {
    // Check if ExpressionEngine says this file is invalid for us
    if (!file.isImage || file.isSVG) {
        UploadErrorHandler(
            setFieldState,
            translations.unusableImage,
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
                translations.unusableImage,
            );
        });
};

export default SelectedFileHandlerEe;
