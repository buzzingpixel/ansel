import * as React from 'react';
import {
    createContext, useContext, useMemo, useState,
} from 'react';
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

    const [timers, setTimers] = useState({});

    const addErrorMessage = (message: string) => {
        setErrorMessages((prevState) => {
            prevState[message] = message;

            return { ...prevState };
        });

        const timer = setTimeout(() => {
            setErrorMessages((prevState) => {
                delete prevState[message];

                return { ...prevState };
            });
        }, 10000);

        setTimers((oldTimers) => {
            if (timers[message]) {
                clearTimeout(timers[message]);
            }

            oldTimers[message] = timer;

            return { ...oldTimers };
        });
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
