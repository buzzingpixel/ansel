import CraftFieldSettingsFieldsRender from './FieldSettings/Craft/Render';
import EEFieldSettingsFieldsRender from './FieldSettings/EE/Render';
import Grid, { GridBindEvent } from './Types/Grid';
import RenderAllSelectsInContainer from './SelectDropdown/RenderAllSelectsInContainer';

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
const eeFieldSettingsGeneral = document.getElementsByClassName(
    'ansel_field_settings_general',
).item(0);
if (eeFieldSettingsGeneral) {
    RenderAllSelectsInContainer(eeFieldSettingsGeneral);
}

const eeFieldSettingsFieldsContainer = document.getElementsByClassName(
    'ee_field_settings_fields',
).item(0);
if (eeFieldSettingsFieldsContainer) {
    EEFieldSettingsFieldsRender(eeFieldSettingsFieldsContainer);
}

/**
 * EE Grid field settings
 */
window.Grid.bind(
    'ansel',
    GridBindEvent.displaySettings,
    (cell) => {
        const fieldSettingsGeneral = cell.find(
            '.ansel_field_settings_general',
        ).get(0);
        if (fieldSettingsGeneral) {
            RenderAllSelectsInContainer(fieldSettingsGeneral);
        }

        const fieldsContainer = cell.find(
            '.ee_field_settings_fields',
        ).get(0);

        EEFieldSettingsFieldsRender(fieldsContainer);
    },
);
