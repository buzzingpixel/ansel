import * as React from 'react';
import ErrorMessagesDisplay from './FieldState/ErrorMessages/ErrorMessagesDisplay';
import useAnselDropZone from './DragNDrop/AnselDropZone';
import DragInProgress from './DragNDrop/DragInProgress';
import { useErrorMessages } from './FieldState/ErrorMessages/ErrorMessagesContext';
import { useProcesses } from './FieldState/Processes/ProcessesContext';
import WorkingIndicator from './FieldState/Processes/WorkingIndicator';

const Field = () => {
    const { hasErrors } = useErrorMessages();

    const { hasProcesses } = useProcesses();

    const {
        getDropZoneRootProps,
        getDropZoneInputProps,
        isDropZoneDragActive,
    } = useAnselDropZone();

    const bgColorClass = hasErrors ? 'ansel_bg-red-50' : 'ansel_bg-gray-50';

    const fieldWorkingClass = hasProcesses ? 'ansel-field-working' : '';

    return (
        <>
            <WorkingIndicator />
            {/* Main field container and dropzone */}
            <div
                className={`${bgColorClass} ${fieldWorkingClass} ansel_border ansel_border-gray-200 ansel_border-solid ansel_relative`}
                style={{ minHeight: '100px' }}
                {...getDropZoneRootProps()}
            >
                {/* Dropzone input */}
                <div className="ansel_hidden">
                    <input {...getDropZoneInputProps()} />
                </div>
                <DragInProgress isDropZoneDragActive={isDropZoneDragActive} />
                <ErrorMessagesDisplay />
            </div>
        </>
    );
};

export default Field;
