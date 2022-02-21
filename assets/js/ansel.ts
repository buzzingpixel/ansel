import Render from './fieldSettings/craft/Render';

const fieldSettingsFieldsContainer = document.getElementsByClassName(
    'field_settings_fields',
).item(0);

if (fieldSettingsFieldsContainer) {
    Render(fieldSettingsFieldsContainer);
}
