import * as React from 'react';
import {
    createContext, useContext, useMemo, useState,
} from 'react';
import { v4 as uuid } from 'uuid';
import ObjectOfStringsType from '../../../Types/ObjectOfStringsType';

interface ErrorMessagesContextType {
    errorMessages: ObjectOfStringsType,
    addErrorMessage:(message: string) => void,
    hasErrors: boolean,
}

const ErrorMessagesContext = createContext<ErrorMessagesContextType>(
    null,
);

const useErrorMessages = () => {
    const context = useContext(ErrorMessagesContext);

    if (!context) {
        throw new Error(
            'useErrorMessages must be used within a ErrorMessagesProvider',
        );
    }

    return context;
};

const ErrorMessagesProvider = (props) => {
    let hasErrors = false;

    const [errorMessages, setErrorMessages] = useState<ObjectOfStringsType>(
        {},
    );

    const addErrorMessage = (message: string) => {
        const id = uuid();

        setErrorMessages((prevState) => {
            prevState[id] = message;

            return { ...prevState };
        });

        setTimeout(() => {
            setErrorMessages((prevState) => {
                delete prevState[id];

                return { ...prevState };
            });
        }, 10000);
    };

    if (Object.keys(errorMessages).length > 0) {
        hasErrors = true;
    }

    const value = useMemo(
        () => ({
            errorMessages,
            addErrorMessage,
            hasErrors,
        }),
        [errorMessages],
    );

    return <ErrorMessagesContext.Provider
        value={value}
        {...props}
    />;
};

export { ErrorMessagesProvider, useErrorMessages };
