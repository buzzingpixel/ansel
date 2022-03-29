import UploadJsonMessageReturn, { UploadJsonMessageReturnType } from './UploadJsonMessageReturn';
import UploadErrorHandler from './UploadErrorHandler';
import FieldDataType from '../../Types/FieldDataType';
import FieldStateType from '../../Types/FieldStateType';
import ImageType from '../../Types/ImageType';

const FileHandler = (
    file: File,
    fieldData: FieldDataType,
    setFieldState: CallableFunction,
) => {
    setFieldState((prevState: FieldStateType) => {
        prevState.processes += 1;

        return { ...prevState };
    });

    const removeProcess = () => {
        setFieldState((prevState: FieldStateType) => {
            prevState.processes -= 1;

            return { ...prevState };
        });
    };

    const formData = new FormData();

    formData.append('uploadKey', fieldData.parameters.uploadKey);

    formData.append(
        'minWidth',
        String(fieldData.fieldSettings.minWidth).toString(),
    );

    formData.append(
        'minHeight',
        String(fieldData.fieldSettings.minHeight).toString(),
    );

    formData.append('image', file);

    fetch(fieldData.parameters.uploadUrl, {
        method: 'POST',
        body: formData,
    }).then((res) => {
        res.json().then((json: UploadJsonMessageReturn) => {
            if (json.type === UploadJsonMessageReturnType.error) {
                UploadErrorHandler(
                    setFieldState,
                    json.message,
                );

                removeProcess();

                return;
            }

            const image = {
                imageUrl: json.base64Image,
            } as ImageType;

            setFieldState((prevState: FieldStateType) => {
                prevState.images = [
                    ...prevState.images,
                    image,
                ];

                return { ...prevState };
            });

            removeProcess();
        }).catch(() => {
            UploadErrorHandler(
                setFieldState,
                fieldData.translations.imageUploadError,
            );

            removeProcess();
        });
    }).catch(() => {
        UploadErrorHandler(
            setFieldState,
            fieldData.translations.imageUploadError,
        );

        removeProcess();
    });
};

export default FileHandler;
