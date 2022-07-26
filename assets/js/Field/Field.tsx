import * as React from 'react';
import { useProcesses } from './FieldState/ProcessesContext';

const Field = () => {
    const { processes, setProcesses } = useProcesses();

    const increment = () => {
        setProcesses(processes + 1);
    };

    return (
        <>
            <div>
                {processes}
            </div>
            <div className="ansel_sr-only">
                todo
            </div>
            <div>
                <a href="#0" onClick={increment}>Increment</a>
            </div>
            foo
        </>
    );
};

export default Field;
