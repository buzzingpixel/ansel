import React = require('react');
import { FieldSettingsProvider } from './FieldSettings/FieldSettingsContext';
import FieldSettingsFromRootEl from './FieldSettings/FieldSettingsFromRootEl';
import { CustomFieldsProvider } from './CustomFields/CustomFieldsContext';
import CustomFieldsFromRootEl from './CustomFields/CustomFieldsFromRootEl';
import { FieldParametersProvider } from './Parameters/FieldParametersContext';
import FieldParametersFromRootEl from './Parameters/FieldParametersFromRootEl';

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
                {props.children}
            </FieldParametersProvider>
        </CustomFieldsProvider>
    </FieldSettingsProvider>;
};

export default FieldProvidersStack;
