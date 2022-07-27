import * as React from 'react';
import ErrorMessagesDisplay from './FieldState/ErrorMessages/ErrorMessagesDisplay';

const Field = () => {
    const bgColorClass = '';
    const fieldWorkingClass = '';

    return (
        <>
            {/* Main field container and dropzone */}
            {/* TODO: {...getRootProps()} */}
            <div
                className={`${bgColorClass} ${fieldWorkingClass} ansel_border ansel_border-gray-200 ansel_border-solid ansel_relative`}
            >
                <ErrorMessagesDisplay />
            </div>
        </>
    );
};

export default Field;
