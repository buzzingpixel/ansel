import * as React from 'react';
import {
    createContext, useContext, useMemo, useState,
} from 'react';

interface ProcessesContextType {
    processes: number,
    setProcesses: CallableFunction,
    hasProcesses: boolean,
    addProcess: () => void,
    removeProcess: () => void,
}

const ProcessesContext = createContext<ProcessesContextType>(null);

const useProcesses = () => {
    const context = useContext(ProcessesContext);

    if (!context) {
        throw new Error(
            'useProcesses must be used within a ProcessesProvider',
        );
    }

    return context;
};

const ProcessesProvider = (props) => {
    let hasProcesses = false;

    const [processes, setProcesses] = useState<number>(0);

    const addProcess = () => {
        setProcesses((prevState) => prevState + 1);
    };

    const removeProcess = () => {
        setProcesses((prevState) => {
            const newState = prevState - 1;

            return newState < 0 ? 0 : newState;
        });
    };

    if (processes > 0) {
        hasProcesses = true;
    }

    const value = useMemo(
        () => ({
            processes,
            setProcesses,
            hasProcesses,
            addProcess,
            removeProcess,
        }),
        [processes],
    );

    return <ProcessesContext.Provider
        value={value}
        {...props}
    />;
};

export { ProcessesProvider, useProcesses };
