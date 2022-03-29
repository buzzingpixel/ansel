import { SpinningCircles } from 'react-loading-icons';
import * as React from 'react';
import FieldStateType from './Types/FieldStateType';

const WorkingIndicator = ({ fieldState }: { fieldState: FieldStateType }) => (
        <div className={fieldState.processes > 0 ? 'ansel_opacity-1' : 'ansel_opacity-0'}>
            <SpinningCircles
                fill="#000"
                stroke="#cdcdcd"
                speed={2}
                width={20}
                height={20}
            />
        </div>
);

export default WorkingIndicator;
