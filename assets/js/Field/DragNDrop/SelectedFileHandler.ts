import { v4 as uuid } from 'uuid';
import EeFileType from '../Platform/EeFileType';
import CraftFileType from '../Platform/CraftFileType';
import { useProcesses } from '../FieldState/Processes/ProcessesContext';
import { useErrorMessages } from '../FieldState/ErrorMessages/ErrorMessagesContext';
import { useTranslations } from '../Translations/TranslationsContext';
import UrlIsImage from '../../Utility/UrlIsImage';
import ImageType from '../FieldState/Images/ImageType';
import useValidateImageConstraints from '../ValidateImageConstraints';
import { useImages } from '../FieldState/Images/ImagesContext';

const useSelectedFileHandler = () => {
    const { addImage } = useImages();
    const { addErrorMessage } = useErrorMessages();
    const { validate } = useValidateImageConstraints();
    const { addProcess, removeProcess } = useProcesses();
    const { unusableImage, dimensionsNotMet } = useTranslations();

    const errorHandler = (message: string) => {
        addErrorMessage(message);

        removeProcess();
    };

    const imageHandler = (image: ImageType) => {
        UrlIsImage(image.imageUrl)
            .then(() => {
                validate(image.imageUrl).then((result) => {
                    if (!result.valid) {
                        errorHandler(dimensionsNotMet);
                    }

                    addImage(image);

                    removeProcess();
                });
            })
            .catch(() => {
                errorHandler(unusableImage);
            });
    };

    const selectedFileHandlerEe = (file: EeFileType) => {
        addProcess();

        if (!file.isImage || file.isSVG) {
            errorHandler(unusableImage);
        }

        const image = {
            id: uuid(),
            imageUrl: file.path,
            sourceImageId: file.file_id.toString(),
        } as ImageType;

        imageHandler(image);
    };

    const selectedFileHandlerCraft = (file: CraftFileType) => {
        addProcess();

        const image = {
            id: uuid(),
            imageUrl: file.url,
            sourceImageId: file.id.toString(),
        } as ImageType;

        imageHandler(image);
    };

    return { selectedFileHandlerEe, selectedFileHandlerCraft };
};

export default useSelectedFileHandler;
