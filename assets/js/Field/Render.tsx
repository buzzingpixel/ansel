import * as React from 'react';
import * as ReactDOM from 'react-dom';
import FieldSettingsType from './FieldSettingsType';
import CustomFieldType from './CustomFieldType';
import Field from './Field';
import FieldParametersType from './FieldParametersType';
import Translations from './Translations';

export default (anselFieldEl: HTMLDivElement) => {
    const fieldSettingsElement = anselFieldEl.getElementsByClassName(
        'ansel_field_settings',
    ).item(0) as HTMLSpanElement;

    const fieldSettings = JSON.parse(
        fieldSettingsElement.dataset.json,
    ) as FieldSettingsType;

    const customFieldsElement = anselFieldEl.getElementsByClassName(
        'ansel_field_custom_fields',
    ).item(0) as HTMLSpanElement;

    const customFields = JSON.parse(
        customFieldsElement.dataset.json,
    ) as Array<CustomFieldType>;

    const parametersElement = anselFieldEl.getElementsByClassName(
        'ansel_field_parameters',
    ).item(0) as HTMLSpanElement;

    const parameters = JSON.parse(
        parametersElement.dataset.json,
    ) as FieldParametersType;

    const translationsElement = anselFieldEl.getElementsByClassName(
        'ansel_translations',
    ).item(0) as HTMLSpanElement;

    const translations = JSON.parse(
        translationsElement.dataset.json,
    ) as Translations;

    ReactDOM.render(
        <Field
            fieldSettings={fieldSettings}
            customFields={customFields}
            parameters={parameters}
            translations={translations}
        />,
        anselFieldEl,
    );
};
