import FileHandler from './FileHandler';
import FieldParametersType from '../../FieldParametersType';
import FieldSettingsType from '../../FieldSettingsType';
import Translations from '../../Translations';

const OnDropAccepted = (
    files: Array<File>,
    setImages: CallableFunction,
    setErrorMessages: CallableFunction,
    parameters: FieldParametersType,
    fieldSettings: FieldSettingsType,
    translations: Translations,
) => {
    files.forEach((file) => {
        FileHandler(
            file,
            setImages,
            setErrorMessages,
            parameters,
            fieldSettings,
            translations,
        );
    });
};

export default OnDropAccepted;
