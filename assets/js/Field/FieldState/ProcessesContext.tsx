import * as React from 'react';
import {
    createContext, useContext, useMemo, useState,
} from 'react';

const ProcessesContext = createContext<{
    processes: number,
    setProcesses: CallableFunction,
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
    const [processes, setProcesses] = useState<number>(0);

    const value = useMemo(
        () => ({ processes, setProcesses }),
        [processes],
    );

    return <ProcessesContext.Provider
        value={value}
        {...props}
    />;
};

export { ProcessesProvider, useProcesses };
