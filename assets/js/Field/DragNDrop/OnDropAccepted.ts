import { v4 as uuid } from 'uuid';
import { useCallback } from 'react';
import { useImages } from '../FieldState/Images/ImagesContext';
import { useProcesses } from '../FieldState/Processes/ProcessesContext';
import { useFieldParameters } from '../Parameters/FieldParametersContext';
import { useFieldSettings } from '../FieldSettings/FieldSettingsContext';
import { useErrorMessages } from '../FieldState/ErrorMessages/ErrorMessagesContext';
import { useTranslations } from '../Translations/TranslationsContext';
import UploadJsonMessageReturn, { UploadJsonMessageReturnType } from './UploadJsonMessageReturn';
import useValidateImageConstraints from '../ValidateImageConstraints';
import ImageType from '../FieldState/Images/ImageType';

const useOnDropAccepted = () => {
    const settings = useFieldSettings();
    const { images, addImage } = useImages();
    const { addErrorMessage } = useErrorMessages();
    const { minWidth, minHeight } = useFieldSettings();
    const { validate } = useValidateImageConstraints();
    const { addProcess, removeProcess } = useProcesses();
    const { uploadKey, uploadUrl } = useFieldParameters();
    const {
        imageUploadError,
        dimensionsNotMet,
        limitedToXImages,
    } = useTranslations();

    const uploadCatch = (errorMsg?: string) => {
        addErrorMessage(errorMsg || imageUploadError);

        removeProcess();
    };

    const addImageFromJson = (json: UploadJsonMessageReturn) => {
        const image = {
            id: uuid(),
            imageUpload: {
                cacheDirectory: json.cacheDirectory,
                cacheFilePath: json.cacheFilePath,
                fileName: json.fileName,
            },
            imageUrl: json.base64Image,
            imageFileName: json.fileName,
        } as ImageType;

        addImage(image);

        removeProcess();
    };

    const uploadThen = (json: UploadJsonMessageReturn) => {
        if (json.type === UploadJsonMessageReturnType.error) {
            uploadCatch(json.message);

            return;
        }

        validate(json.base64Image)
            .then((result) => {
                if (!result.valid) {
                    uploadCatch(dimensionsNotMet);
                }

                addImageFromJson(json);
            })
            .catch((error) => {
                uploadCatch(error);
            });
    };

    const fileHandler = (file: File) => {
        addProcess();

        const formData = new FormData();

        formData.append('uploadKey', uploadKey);

        formData.append('minWidth', String(minWidth).toString());

        formData.append('minHeight', String(minHeight).toString());

        formData.append('image', file);

        fetch(uploadUrl, { method: 'POST', body: formData })
            .then((res) => {
                res.json().then(uploadThen).catch(uploadCatch);
            })
            .catch(uploadCatch);
    };

    const onDropAccepted = useCallback(
        (files: Array<File>) => {
            const projectedTotal = images.length + files.length;

            if (
                settings.maxQty > 0
                && settings.preventUploadOverMax
                && projectedTotal > settings.maxQty
            ) {
                addErrorMessage(limitedToXImages);

                const deleteCount = projectedTotal - settings.maxQty;

                files.splice(0, deleteCount);
            }

            files.forEach(fileHandler);
        },
        [images],
    );

    return ({
        onDropAccepted,
    });
};

export default useOnDropAccepted;
