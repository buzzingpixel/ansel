import * as React from 'react';
import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import FieldSettingsType from './Types/FieldSettingsType';
import CustomFieldType from './CustomFieldType';
import Field from './Field';
import FieldParametersType from './Types/FieldParametersType';
import TranslationsType from './Types/TranslationsType';
import PlatformType from './Types/PlatformType';

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
    ) as TranslationsType;

    const platformElement = anselFieldEl.getElementsByClassName(
        'ansel_platform',
    ).item(0) as HTMLSpanElement;

    const platform = JSON.parse(
        platformElement.dataset.json,
    ) as PlatformType;

    const inputPlaceholder = anselFieldEl.getElementsByClassName(
        'ansel_field_input_placeholder',
    ).item(0) as HTMLInputElement;

    // Render field
    const field = createRoot(anselFieldEl);
    field.render(
        <StrictMode>
            <Field
                fieldSettings={fieldSettings}
                customFields={customFields}
                parameters={parameters}
                translations={translations}
                platform={platform}
                inputPlaceholder={inputPlaceholder}
            />
        </StrictMode>,
    );
};
