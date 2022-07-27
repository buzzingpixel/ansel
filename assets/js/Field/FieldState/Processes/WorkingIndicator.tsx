import { SpinningCircles } from 'react-loading-icons';
import * as React from 'react';
import { useProcesses } from './ProcessesContext';

const WorkingIndicator = () => {
    const { hasProcesses } = useProcesses();

    return (
        <div className={hasProcesses ? 'ansel_opacity-1' : 'ansel_opacity-0'}>
            <SpinningCircles
                fill="#000"
                stroke="#cdcdcd"
                speed={2}
                width={20}
                height={20}
            />
        </div>
    );
};

export default WorkingIndicator;
