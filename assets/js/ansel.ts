import CraftFieldSettingsFieldsRender from './FieldSettings/Craft/Render';

const craftFieldSettingsFieldsContainer = document.getElementsByClassName(
    'craft_field_settings_fields',
).item(0);

if (craftFieldSettingsFieldsContainer) {
    CraftFieldSettingsFieldsRender(craftFieldSettingsFieldsContainer);
}
