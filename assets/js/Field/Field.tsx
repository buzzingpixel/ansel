import * as React from 'react';
import ErrorMessagesDisplay from './FieldState/ErrorMessages/ErrorMessagesDisplay';
import useAnselDropZone from './DragNDrop/AnselDropZone';
import DragInProgress from './DragNDrop/DragInProgress';
import { useErrorMessages } from './FieldState/ErrorMessages/ErrorMessagesContext';

const Field = () => {
    const { hasErrors } = useErrorMessages();

    const {
        getDropZoneRootProps,
        isDropZoneDragActive,
    } = useAnselDropZone();

    const bgColorClass = hasErrors ? 'ansel_bg-red-50' : 'ansel_bg-gray-50';

    const fieldWorkingClass = '';

    return (
        <>
            {/* Main field container and dropzone */}
            <div
                className={`${bgColorClass} ${fieldWorkingClass} ansel_border ansel_border-gray-200 ansel_border-solid ansel_relative`}
                style={{ minHeight: '100px' }}
                {...getDropZoneRootProps()}
            >
                <DragInProgress isDropZoneDragActive={isDropZoneDragActive} />
                <ErrorMessagesDisplay />
            </div>
        </>
    );
};

export default Field;
