import FieldParametersType from '../../FieldParametersType';
import UploadJsonMessageReturn, { UploadJsonMessageReturnType } from './UploadJsonMessageReturn';
import UploadErrorHandler from './UploadErrorHandler';
import FieldSettingsType from '../../FieldSettingsType';
import Translations from '../../Translations';
import Image from '../../Image';

const FileHandler = (
    file: File,
    setImages: CallableFunction,
    setErrorMessages: CallableFunction,
    parameters: FieldParametersType,
    fieldSettings: FieldSettingsType,
    translations: Translations,
) => {
    const formData = new FormData();

    formData.append('uploadKey', parameters.uploadKey);

    formData.append(
        'minWidth',
        String(fieldSettings.minWidth).toString(),
    );

    formData.append(
        'minHeight',
        String(fieldSettings.minHeight).toString(),
    );

    formData.append('image', file);

    fetch(parameters.uploadUrl, {
        method: 'POST',
        body: formData,
    }).then((res) => {
        res.json().then((json: UploadJsonMessageReturn) => {
            if (json.type === UploadJsonMessageReturnType.error) {
                UploadErrorHandler(
                    setErrorMessages,
                    json.message,
                );

                return;
            }

            const image = {
                imageUrl: json.base64Image,
            } as Image;

            setImages((prevState) => [
                ...prevState,
                image,
            ]);
        }).catch(() => {
            UploadErrorHandler(
                setErrorMessages,
                translations.imageUploadError,
            );
        });
    }).catch(() => {
        UploadErrorHandler(
            setErrorMessages,
            translations.imageUploadError,
        );
    });
};

export default FileHandler;
