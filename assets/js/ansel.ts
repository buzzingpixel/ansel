import CraftFieldSettingsFieldsRender from './FieldSettings/Craft/Render';
import EEFieldSettingsFieldsRender from './FieldSettings/EE/Render';
import Grid, { GridBindEvent } from './Types/Grid';
import RenderAllSelectsInContainer from './SelectDropdown/RenderAllSelectsInContainer';
import FieldRender from './Field/Render';

/**
 * Declare the EE Grid global
 */
declare global {
    interface Window { Grid: Grid | null; }
}

/**
 * If there are any Craft fields, initialize them
 */
const craftFieldSettingsFieldsContainer = document.getElementsByClassName(
    'craft_field_settings_fields',
).item(0);
if (craftFieldSettingsFieldsContainer) {
    CraftFieldSettingsFieldsRender(craftFieldSettingsFieldsContainer);
}

/**
 * If there are any general field settings containers, initialize them
 * (applies to all CMSes)
 */
const fieldSettingsGeneral = document.getElementsByClassName(
    'ansel_field_settings_general',
).item(0);
if (fieldSettingsGeneral) {
    /**
     * Do not initialize any Blocks fields, it effs up the placeholders and
     * we'll initialize with a displaySettings even anyway
     */
    const blockContainer = fieldSettingsGeneral.closest(
        '.block-container',
    ) as HTMLDivElement | null;

    if (!blockContainer) {
        RenderAllSelectsInContainer(fieldSettingsGeneral);
    }
}

/**
 * Initialize EE field settings fields if there are any
 */
const eeFieldSettingsFieldsContainer = document.getElementsByClassName(
    'ee_field_settings_fields',
).item(0);
if (eeFieldSettingsFieldsContainer) {
    /**
     * Do not initialize any Blocks fields, it effs up the placeholders and
     * we'll initialize with a displaySettings even anyway
     */
    const blockContainer = eeFieldSettingsFieldsContainer.closest(
        '.block-container',
    ) as HTMLDivElement | null;

    if (!blockContainer) {
        EEFieldSettingsFieldsRender(eeFieldSettingsFieldsContainer);
    }
}

/**
 * If the EE Grid is defined, declare event listeners (this also defines Blocks
 * listeners)
 */
if (window.Grid) {
    /**
     * The displaySettings Grid event
     */
    window.Grid.bind(
        'ansel',
        GridBindEvent.displaySettings,
        (cell) => {
            /**
             * If there are any general field settings containers, initialize
             * them
             */
            const gridFieldSettingsGeneral = cell.find(
                '.ansel_field_settings_general',
            ).get(0);
            if (gridFieldSettingsGeneral) {
                RenderAllSelectsInContainer(gridFieldSettingsGeneral);
            }

            /**
             * Initialize field settings fields
             */
            const fieldsContainer = cell.find(
                '.ee_field_settings_fields',
            ).get(0);
            EEFieldSettingsFieldsRender(fieldsContainer);
        },
    );
}

/**
 * Init ansel general fields
 */
const anselFieldEls = document.getElementsByClassName(
    'ansel_field',
);
for (let i = 0; i < anselFieldEls.length; i += 1) {
    const anselFieldEl = anselFieldEls[i] as HTMLDivElement;

    FieldRender(anselFieldEl);
}
