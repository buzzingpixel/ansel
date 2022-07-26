import { createRoot } from 'react-dom/client';
import { StrictMode } from 'react';
import * as React from 'react';
import Field from './Field';
import FieldProvidersStack from './FieldProvidersStack';

export default (anselFieldEl: HTMLDivElement) => {
    const field = createRoot(anselFieldEl);

    field.render(
        <StrictMode>
            <FieldProvidersStack anselFieldEl={anselFieldEl}>
                <Field />
            </FieldProvidersStack>
        </StrictMode>,
    );
};
