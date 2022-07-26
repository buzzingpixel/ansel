import * as React from 'react';
import { createContext, useContext } from 'react';
import FieldParametersType from './FieldParametersType';

const FieldParametersContext = createContext<FieldParametersType>({
    environment: '',
    uploadKey: '',
    uploadUrl: '',
    processingUrl: '',
});

const useFieldParameters = () => {
    const context = useContext(FieldParametersContext);

    if (!context) {
        throw new Error(
            'useFieldParameters must be used within a FieldParametersProvider',
        );
    }

    return context;
};

const FieldParametersProvider = (props) => <FieldParametersContext.Provider
    value={props.fieldParameters}
    {...props}
/>;

export { FieldParametersProvider, useFieldParameters };
