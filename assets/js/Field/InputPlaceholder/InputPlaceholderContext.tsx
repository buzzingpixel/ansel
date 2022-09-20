import * as React from 'react';
import { createContext, useContext } from 'react';

const InputPlaceholderContext = createContext<HTMLInputElement>(
    undefined,
);

const useInputPlaceholder = () => {
    const context = useContext(InputPlaceholderContext);

    if (!context) {
        throw new Error(
            'useInputPlaceholder must be used within a InputPlaceholderProvider',
        );
    }

    return context;
};

const InputPlaceholderProvider = (props) => <InputPlaceholderContext.Provider
    value={props.inputPlaceholder}
    {...props}
/>;

export { InputPlaceholderProvider, useInputPlaceholder };
