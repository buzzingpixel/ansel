import CraftFieldSettingsFieldsRender from './FieldSettings/Craft/Render';
import EEFieldSettingsFieldsRender from './FieldSettings/EE/Render';

/**
 * Craft field settings
 */
const craftFieldSettingsFieldsContainer = document.getElementsByClassName(
    'craft_field_settings_fields',
).item(0);
if (craftFieldSettingsFieldsContainer) {
    CraftFieldSettingsFieldsRender(craftFieldSettingsFieldsContainer);
}

/**
 * EE Field Settings
 */
const eeFieldSettingsFieldsContainer = document.getElementsByClassName(
    'ee_field_settings_fields',
).item(0);
if (eeFieldSettingsFieldsContainer) {
    EEFieldSettingsFieldsRender(eeFieldSettingsFieldsContainer);
}
