import CraftFileType from './CraftFileType';
import ImageType from '../Types/ImageType';
import FieldStateType from '../Types/FieldStateType';

const SelectedFileHandlerCraft = (
    file: CraftFileType,
    setFieldState: CallableFunction,
) => {
    // TODO: Make sure is image and not SVG

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
};

export default SelectedFileHandlerCraft;
