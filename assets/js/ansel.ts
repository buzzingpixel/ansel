import CraftFieldSettingsFieldsRender from './FieldSettings/Craft/Render';
import EEFieldSettingsFieldsRender from './FieldSettings/EE/Render';
import Grid, { GridBindEvent } from './Types/Grid';

declare global {
    interface Window { Grid: Grid; }
}

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

// Grid field settings
window.Grid.bind(
    'ansel',
    GridBindEvent.displaySettings,
    (cell) => {
        const fieldsContainer = cell.find(
            '.ee_field_settings_fields',
        ).get(0);

        EEFieldSettingsFieldsRender(fieldsContainer);
    },
);
