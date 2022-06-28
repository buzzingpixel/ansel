import { v4 as uuid } from 'uuid';
import EeFileType from './EeFileType';
import ImageType from '../Types/ImageType';
import FieldStateType from '../Types/FieldStateType';
import UploadErrorHandler from '../DropHandlers/DropAccepted/UploadErrorHandler';
import UrlIsImage from '../../Utility/UrlIsImage';
import ValidateImageConstraints from '../../Utility/ValidateImageConstraints';
import FieldDataType from '../Types/FieldDataType';

const SelectedFileHandlerEe = (
    file: EeFileType,
    setFieldState: CallableFunction,
    fieldData: FieldDataType,
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

    // Check if ExpressionEngine says this file is invalid for us
    if (!file.isImage || file.isSVG) {
        UploadErrorHandler(
            setFieldState,
            fieldData.translations.unusableImage,
        );

        removeProcess();

        return;
    }

    // Do our own check
    UrlIsImage(file.path)
        .then(() => {
            ValidateImageConstraints(
                file.path,
                fieldData.fieldSettings,
            )
                .then((validation) => {
                    if (!validation.valid) {
                        UploadErrorHandler(
                            setFieldState,
                            fieldData.translations.dimensionsNotMet,
                        );

                        removeProcess();

                        return;
                    }

                    const image = {
                        uid: uuid(),
                        imageUrl: file.path,
                        sourceImageId: file.file_id.toString(),
                    } as ImageType;

                    setFieldState((prevState: FieldStateType) => {
                        prevState.images = [
                            ...prevState.images,
                            image,
                        ];

                        return { ...prevState };
                    });

                    removeProcess();
                })
                .catch((error) => {
                    UploadErrorHandler(
                        setFieldState,
                        fieldData.translations[error.toString()],
                    );

                    removeProcess();
                });
        })
        .catch(() => {
            UploadErrorHandler(
                setFieldState,
                fieldData.translations.unusableImage,
            );

            removeProcess();
        });
};

export default SelectedFileHandlerEe;
