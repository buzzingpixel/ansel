import * as React from 'react';
import { createContext, useContext } from 'react';
import FieldSettingsType from './FieldSettingsType';

const FieldSettingsContext = createContext<FieldSettingsType>({
    uploadLocation: '',
    saveLocation: '',
    minQty: 0,
    maxQty: 0,
    preventUploadOverMax: false,
    quality: 0,
    forceJpg: false,
    retinaMode: false,
    minWidth: 0,
    minHeight: 0,
    maxWidth: 0,
    maxHeight: 0,
    ratio: '',
});

const useFieldSettings = () => {
    const context = useContext(FieldSettingsContext);

    if (!context) {
        throw new Error(
            'useFieldSettings must be used within a FieldSettingsProvider',
        );
    }

    return context;
};

const FieldSettingsProvider = (props) => <FieldSettingsContext.Provider
    value={props.fieldSettings}
    {...props}
/>;

export { FieldSettingsProvider, useFieldSettings };
