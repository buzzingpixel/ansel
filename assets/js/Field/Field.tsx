import * as React from 'react';
import ErrorMessagesDisplay from './FieldState/ErrorMessages/ErrorMessagesDisplay';
import useAnselDropZone from './DragNDrop/AnselDropZone';
import DragInProgress from './DragNDrop/DragInProgress';
import { useErrorMessages } from './FieldState/ErrorMessages/ErrorMessagesContext';
import { useProcesses } from './FieldState/Processes/ProcessesContext';
import WorkingIndicator from './FieldState/Processes/WorkingIndicator';
import Uploading from './DragNDrop/Uploading';
import RenderImages from './FieldState/Images/RenderImages';
import FieldInputs from './FieldState/FieldInputs';
import { useImages } from './FieldState/Images/ImagesContext';
import { useFieldSettings } from './FieldSettings/FieldSettingsContext';
import UploadOverQty from './FieldState/UploadOverQty';
import UploadUnderQty from './FieldState/UploadUnderQty';

const Field = () => {
    const { hasErrors } = useErrorMessages();

    const { hasProcesses } = useProcesses();

    const { images } = useImages();

    const settings = useFieldSettings();

    const {
        getDropZoneRootProps,
        getDropZoneInputProps,
        openDropZoneDeviceDialog,
        isDropZoneDragActive,
    } = useAnselDropZone();

    const bgColorClass = hasErrors ? 'ansel_bg-red-50' : 'ansel_bg-gray-50';

    const fieldWorkingClass = hasProcesses ? 'ansel-field-working' : '';

    let showUploader = true;

    if (
        settings.maxQty > 0
        && settings.preventUploadOverMax
        && images.length >= settings.maxQty
    ) {
        showUploader = false;
    }

    return (
        <div className="ansel_box-border">
            <div className="ansel_sr-only">
                <FieldInputs />
            </div>
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
                <UploadUnderQty />
                <UploadOverQty />
                <ErrorMessagesDisplay />
                {/* Primary field elements */}
                <div className="ansel_p-4 ansel_overflow-auto">
                    {showUploader && (
                        <Uploading
                            openDropZoneDeviceDialog={openDropZoneDeviceDialog}
                        />
                    )}
                    <RenderImages />
                    {showUploader && images.length > 3 && (
                        <div className="ansel_pt-6">
                            <Uploading
                                openDropZoneDeviceDialog={openDropZoneDeviceDialog}
                            />
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
};

export default Field;
