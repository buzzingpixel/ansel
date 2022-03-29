import FieldSettingsType from './FieldSettingsType';
import CustomFieldType from '../CustomFieldType';
import FieldParametersType from './FieldParametersType';
import TranslationsType from './TranslationsType';

interface FieldDataType {
    fieldSettings: FieldSettingsType,
    customFields: Array<CustomFieldType>,
    parameters: FieldParametersType,
    translations: TranslationsType,
}

export default FieldDataType;
