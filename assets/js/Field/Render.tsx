import { createRoot } from 'react-dom/client';
import { StrictMode } from 'react';
import * as React from 'react';
import Field from './Field';
import { FieldSettingsProvider } from './FieldSettings/FieldSettingsContext';
import FieldSettingsFromRootEl from './FieldSettings/FieldSettingsFromRootEl';
import { CustomFieldsProvider } from './CustomFields/CustomFieldsContext';
import CustomFieldsFromRootEl from './CustomFields/CustomFieldsFromRootEl';

export default (anselFieldEl: HTMLDivElement) => {
    const field = createRoot(anselFieldEl);

    field.render(
        <StrictMode>
            <FieldSettingsProvider
                fieldSettings={FieldSettingsFromRootEl(anselFieldEl)}
            >
                <CustomFieldsProvider
                    customFields={CustomFieldsFromRootEl(anselFieldEl)}
                >
                    <Field />
                </CustomFieldsProvider>
            </FieldSettingsProvider>
        </StrictMode>,
    );
};
