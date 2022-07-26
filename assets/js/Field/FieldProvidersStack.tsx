import React = require('react');
import { FieldSettingsProvider } from './FieldSettings/FieldSettingsContext';
import FieldSettingsFromRootEl from './FieldSettings/FieldSettingsFromRootEl';
import { CustomFieldsProvider } from './CustomFields/CustomFieldsContext';
import CustomFieldsFromRootEl from './CustomFields/CustomFieldsFromRootEl';
import { FieldParametersProvider } from './Parameters/FieldParametersContext';
import FieldParametersFromRootEl from './Parameters/FieldParametersFromRootEl';
import { TranslationsProvider } from './Translations/TranslationsContext';
import TranslationsFromRootEl from './Translations/TranslationsFromRootEl';

const FieldProvidersStack = (props) => {
    const root = props.anselFieldEl as HTMLDivElement;

    return <FieldSettingsProvider
        fieldSettings={FieldSettingsFromRootEl(root)}
    >
        <CustomFieldsProvider
            customFields={CustomFieldsFromRootEl(root)}
        >
            <FieldParametersProvider
                fieldParameters={FieldParametersFromRootEl(root)}
            >
                <TranslationsProvider
                    translations={TranslationsFromRootEl(root)}
                >
                    {props.children}
                </TranslationsProvider>
            </FieldParametersProvider>
        </CustomFieldsProvider>
    </FieldSettingsProvider>;
};

export default FieldProvidersStack;
