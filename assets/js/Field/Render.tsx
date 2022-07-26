import { createRoot } from 'react-dom/client';
import { StrictMode } from 'react';
import * as React from 'react';
import Field from './Field';
import { FieldSettingsProvider } from './FieldSettings/FieldSettingsContext';
import FieldSettingsFromRootEl from './FieldSettings/FieldSettingsFromRootEl';

export default (anselFieldEl: HTMLDivElement) => {
    const field = createRoot(anselFieldEl);

    field.render(
        <StrictMode>
            <FieldSettingsProvider
                fieldSettings={FieldSettingsFromRootEl(anselFieldEl)}
            >
                <Field />
            </FieldSettingsProvider>
        </StrictMode>,
    );
};
