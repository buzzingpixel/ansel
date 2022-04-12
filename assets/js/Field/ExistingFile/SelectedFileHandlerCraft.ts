import CraftFileType from './CraftFileType';
import ImageType from '../Types/ImageType';
import FieldStateType from '../Types/FieldStateType';
import UrlIsImage from '../../Utility/UrlIsImage';
import UploadErrorHandler from '../DropHandlers/DropAccepted/UploadErrorHandler';
import TranslationsType from '../Types/TranslationsType';

const SelectedFileHandlerCraft = (
    file: CraftFileType,
    setFieldState: CallableFunction,
    translations: TranslationsType,
) => {
    // TODO: We seem to be getting images from the Ansel Save directory
    UrlIsImage(file.url)
        .then(() => {
            // TODO: Make sure image meets requirements
            const image = {
                imageUrl: file.url,
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

export default SelectedFileHandlerCraft;
