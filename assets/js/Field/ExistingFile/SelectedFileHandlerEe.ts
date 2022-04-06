import EeFileType from './EeFileType';
import ImageType from '../Types/ImageType';
import FieldStateType from '../Types/FieldStateType';

const SelectedFileHandlerEe = (
    file: EeFileType,
    setFieldState: CallableFunction,
) => {
    if (!file.isImage || file.isSVG) {
        console.log('todo');

        return;
    }

    // TODO, check if image meets requirements

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
};

export default SelectedFileHandlerEe;
