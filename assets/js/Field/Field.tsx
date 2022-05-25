import * as React from 'react';
import { useCallback, useState } from 'react';
import { FileRejection, useDropzone } from 'react-dropzone';
import { SortableContainer, SortableElement } from 'react-sortable-hoc';
import FieldUploadSelect from './ExistingFile/FieldUploadSelect';
import DragInProgress from './DragInProgress';
import FieldError from './FieldError';
import OnDropRejected from './DropHandlers/DropRejected/OnDropRejected';
import OnDropAccepted from './DropHandlers/DropAccepted/OnDropAccepted';
import FieldStateType from './Types/FieldStateType';
import FieldDataType from './Types/FieldDataType';
import WorkingIndicator from './WorkingIndicator';
import FieldImage from './FieldImage';
import ArrayMove from '../Utility/ArrayMove';
import FieldInputs from './FieldInputs';

const SortableFieldContainer = SortableContainer(
    ({ children }) => (
        <ul
            role="list"
            className="ansel_grid ansel_grid-cols-1 ansel_gap-6 md:ansel_grid-cols-2 2xl:ansel_grid-cols-3 3xl:ansel_grid-cols-4"
        >
            {children}
        </ul>
    ),
);

const SortableFieldItem = SortableElement(
    (props) => <FieldImage {...props} />,
);

const Field = (fieldData: FieldDataType) => {
    const [fieldState, setFieldState] = useState<FieldStateType>({
        processes: 0,
        errorMessages: {},
        images: [],
    });

    const onDropAccepted = useCallback(
        (files: Array<File>) => {
            OnDropAccepted(files, fieldData, setFieldState);
        },
        [
            fieldState,
            setFieldState,
        ],
    );

    const onDropRejected = useCallback(
        (rejected: Array<FileRejection>) => {
            OnDropRejected(rejected, setFieldState);
        },
        [
            fieldState,
            setFieldState,
        ],
    );

    const {
        getRootProps,
        getInputProps,
        open,
        isDragActive,
    } = useDropzone(
        {
            onDropAccepted,
            onDropRejected,
            noClick: true,
            noKeyboard: true,
            accept: 'image/jpeg, image/png, image/gif',
        },
    );

    let uploaderClass = '';

    if (fieldState.images.length > 0) {
        uploaderClass = 'ansel_pb-4';
    }

    let bgColorClass = 'ansel_bg-gray-50';

    if (Object.keys(fieldState.errorMessages).length > 0) {
        bgColorClass = 'ansel_bg-red-50';
    }

    let fieldWorkingClass = '';

    if (fieldState.processes > 0) {
        fieldWorkingClass = 'ansel-field-working';
    }

    const onSortEnd = useCallback(({ oldIndex, newIndex }) => {
        setFieldState((oldState) => {
            const images = ArrayMove(oldState.images, oldIndex, newIndex);

            return {
                ...oldState,
                images,
            };
        });
    }, []);

    return (
        <>
            <div className="ansel_sr-only">
                <FieldInputs fieldData={fieldData} fieldState={fieldState} />
            </div>
            <WorkingIndicator fieldState={fieldState} />
            <div
                className={`${bgColorClass} ${fieldWorkingClass} ansel_border ansel_border-gray-200 ansel_border-solid ansel_relative`}
                {...getRootProps()}
            >
                { isDragActive && <DragInProgress /> }
                {
                    Object.keys(fieldState.errorMessages).map((errorKey) => (
                        <FieldError errorMessage={fieldState.errorMessages[errorKey]} />
                    ))
                }
                <input {...getInputProps()} />
                <div className="ansel_p-4 ansel_overflow-auto">
                    <div className={uploaderClass}>
                        <FieldUploadSelect
                            dropZoneOpenDeviceDialog={open}
                            setFieldState={setFieldState}
                            fieldData={fieldData}
                        />
                    </div>
                    {/* eslint-disable-next-line @typescript-eslint/ban-ts-comment */}
                    {/* @ts-ignore */}
                    <SortableFieldContainer
                        onSortEnd={onSortEnd}
                        axis="xy"
                        helperClass="ansel_cursor-grabbing"
                        useDragHandle={true}
                    >
                        {fieldState.images.map((image, index) => (
                            <SortableFieldItem
                                key={image.uid}
                                // eslint-disable-next-line @typescript-eslint/ban-ts-comment
                                // @ts-ignore
                                setFieldState={setFieldState}
                                image={image}
                                index={index}
                                rowIndex={index}
                            />
                        ))}
                    </SortableFieldContainer>
                    {fieldState.images.length > 4
                        && <div className="ansel_pt-6">
                            <FieldUploadSelect
                                dropZoneOpenDeviceDialog={open}
                                setFieldState={setFieldState}
                                fieldData={fieldData}
                            />
                        </div>
                    }
                </div>
            </div>
            {fieldState.images.length > 4
                && <div className="ansel_pt-4">
                    <WorkingIndicator fieldState={fieldState} />
                </div>
            }
        </>
    );
};

export default Field;
