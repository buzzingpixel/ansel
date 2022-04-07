import CraftFileType from './CraftFileType';
import ImageType from '../Types/ImageType';
import FieldStateType from '../Types/FieldStateType';
import UrlIsImage from '../../Utility/UrlIsImage';
import UploadErrorHandler from '../DropHandlers/DropAccepted/UploadErrorHandler';

const SelectedFileHandlerCraft = (
    file: CraftFileType,
    setFieldState: CallableFunction,
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
                // TODO: Get from translations
                'The selected file is not a usable image',
            );
        });
};

export default SelectedFileHandlerCraft;
