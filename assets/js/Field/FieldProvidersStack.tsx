import React = require('react');
import { FieldSettingsProvider } from './FieldSettings/FieldSettingsContext';
import FieldSettingsFromRootEl from './FieldSettings/FieldSettingsFromRootEl';
import { CustomFieldsProvider } from './CustomFields/CustomFieldsContext';
import CustomFieldsFromRootEl from './CustomFields/CustomFieldsFromRootEl';
import { FieldParametersProvider } from './Parameters/FieldParametersContext';
import FieldParametersFromRootEl from './Parameters/FieldParametersFromRootEl';
import { TranslationsProvider } from './Translations/TranslationsContext';
import TranslationsFromRootEl from './Translations/TranslationsFromRootEl';
import { PlatformProvider } from './Platform/PlatformContext';
import PlatformFromRootEl from './Platform/PlatformFromRootEl';
import { InputPlaceholderProvider } from './InputPlaceholder/InputPlaceholderContext';
import InputPlaceholderFromRootEl from './InputPlaceholder/InputPlaceholderFromRootEl';
import { ProcessesProvider } from './FieldState/ProcessesContext';

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
                    <PlatformProvider
                        platform={PlatformFromRootEl(root)}
                    >
                        <InputPlaceholderProvider
                            inputPlaceholder={InputPlaceholderFromRootEl(root)}
                        >
                            <ProcessesProvider>
                                {props.children}
                            </ProcessesProvider>
                        </InputPlaceholderProvider>
                    </PlatformProvider>
                </TranslationsProvider>
            </FieldParametersProvider>
        </CustomFieldsProvider>
    </FieldSettingsProvider>;
};

export default FieldProvidersStack;
