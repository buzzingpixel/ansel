import { v4 as uuid } from 'uuid';
import CraftFileType from './CraftFileType';
import ImageType from '../Types/ImageType';
import FieldStateType from '../Types/FieldStateType';
import UrlIsImage from '../../Utility/UrlIsImage';
import UploadErrorHandler from '../DropHandlers/DropAccepted/UploadErrorHandler';
import FieldDataType from '../Types/FieldDataType';
import ValidateImageConstraints from '../../Utility/ValidateImageConstraints';

const SelectedFileHandlerCraft = (
    file: CraftFileType,
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

    UrlIsImage(file.url)
        .then(() => {
            ValidateImageConstraints(
                file.url,
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
                        imageUrl: file.url,
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

export default SelectedFileHandlerCraft;
