import * as React from 'react';
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
import { ProcessesProvider } from './FieldState/Processes/ProcessesContext';
import { ErrorMessagesProvider } from './FieldState/ErrorMessages/ErrorMessagesContext';
import { ImagesProvider } from './FieldState/Images/ImagesContext';
import { RootElProvider } from './RootElProvider';
import { KeyboardProvider } from './Keyboard/KeyboardContext';
import DataFromRootEl from './Data/DataFromRootEl';

const FieldProvidersStack = (props) => {
    const root = props.anselFieldEl as HTMLDivElement;

    const data = DataFromRootEl(root);

    return <RootElProvider rootEl={root}>
        <FieldSettingsProvider
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
                                    <ErrorMessagesProvider>
                                        {/* TODO: set existing images here */}
                                        <ImagesProvider
                                            images={data.images}
                                            deletions={data.deletions}
                                        >
                                            <KeyboardProvider>
                                                {props.children}
                                            </KeyboardProvider>
                                        </ImagesProvider>
                                    </ErrorMessagesProvider>
                                </ProcessesProvider>
                            </InputPlaceholderProvider>
                        </PlatformProvider>
                    </TranslationsProvider>
                </FieldParametersProvider>
            </CustomFieldsProvider>
        </FieldSettingsProvider>
    </RootElProvider>;
};

export default FieldProvidersStack;
