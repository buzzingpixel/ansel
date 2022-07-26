import React = require('react');
import { FieldSettingsProvider } from './FieldSettings/FieldSettingsContext';
import FieldSettingsFromRootEl from './FieldSettings/FieldSettingsFromRootEl';
import { CustomFieldsProvider } from './CustomFields/CustomFieldsContext';
import CustomFieldsFromRootEl from './CustomFields/CustomFieldsFromRootEl';

const FieldProvidersStack = (props) => {
    const anselFieldEl = props.anselFieldEl as HTMLDivElement;

    return <FieldSettingsProvider
        fieldSettings={FieldSettingsFromRootEl(anselFieldEl)}
    >
        <CustomFieldsProvider
            customFields={CustomFieldsFromRootEl(anselFieldEl)}
        >
            {props.children}
        </CustomFieldsProvider>
    </FieldSettingsProvider>;
};

export default FieldProvidersStack;
