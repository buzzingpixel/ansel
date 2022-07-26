import * as React from 'react';
import { createContext, useContext } from 'react';
import CustomFieldType from './CustomFieldType';

const CustomFieldsContext = createContext<Array<CustomFieldType>>(
    [],
);

const useCustomFields = () => {
    const context = useContext(CustomFieldsContext);

    if (!context) {
        throw new Error(
            'useCustomFields must be used within a CustomFieldsProvider',
        );
    }

    return context;
};

const CustomFieldsProvider = (props) => <CustomFieldsContext.Provider
    value={props.customFields}
    {...props}
/>;

export { CustomFieldsProvider, useCustomFields };
