import * as React from 'react';
import {
    createContext, useContext, useMemo, useState,
} from 'react';

const ProcessesContext = createContext<{
    processes: number,
    setProcesses: CallableFunction,
    hasProcesses: boolean,
}>(null);

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

    if (processes > 0) {
        hasProcesses = true;
    }

    const value = useMemo(
        () => ({
            processes,
            setProcesses,
            hasProcesses,
        }),
        [processes],
    );

    return <ProcessesContext.Provider
        value={value}
        {...props}
    />;
};

export { ProcessesProvider, useProcesses };
