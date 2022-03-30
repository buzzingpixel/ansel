import FieldSettingsType from './FieldSettingsType';
import CustomFieldType from '../CustomFieldType';
import FieldParametersType from './FieldParametersType';
import TranslationsType from './TranslationsType';
import PlatformType from './PlatformType';

interface FieldDataType {
    fieldSettings: FieldSettingsType;
    customFields: Array<CustomFieldType>;
    parameters: FieldParametersType;
    translations: TranslationsType;
    platform: PlatformType;
}

export default FieldDataType;
