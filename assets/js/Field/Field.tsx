import * as React from 'react';
import { useCallback, useState } from 'react';
import { FileRejection, useDropzone } from 'react-dropzone';
import { IconContext } from 'react-icons';
import { ImCrop } from 'react-icons/im';
import { FiEdit } from 'react-icons/fi';
import { CgEditBlackPoint } from 'react-icons/cg';
import { MdDelete } from 'react-icons/md';
import FieldUploadSelect from './ExistingFile/FieldUploadSelect';
import DragInProgress from './DragInProgress';
import FieldError from './FieldError';
import OnDropRejected from './DropHandlers/DropRejected/OnDropRejected';
import OnDropAccepted from './DropHandlers/DropAccepted/OnDropAccepted';
import FieldStateType from './Types/FieldStateType';
import FieldDataType from './Types/FieldDataType';
import WorkingIndicator from './WorkingIndicator';
import DeleteButton from './InteractionHandlers/DeleteButton';

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

    return (
        <>
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
                <div className="ansel_p-4">
                    <div className={uploaderClass}>
                        <FieldUploadSelect
                            dropZoneOpenDeviceDialog={open}
                            setFieldState={setFieldState}
                            fieldData={fieldData}
                        />
                    </div>
                    <ul
                        role="list"
                        className="ansel_grid ansel_grid-cols-1 ansel_gap-6 md:ansel_grid-cols-2 2xl:ansel_grid-cols-3 3xl:ansel_grid-cols-4"
                    >
                        {fieldState.images.map((image, index) => (
                            <li
                                key={index}
                                className="ansel_col-span-1 ansel_flex ansel_flex-col ansel_text-center ansel_bg-white ansel_rounded-lg ansel_shadow ansel_divide-y ansel_divide-gray-200"
                            >
                                <div className="ansel_flex-1 ansel_flex ansel_flex-col ansel_p-8">
                                    <img
                                        className="ansel_w-48 ansel_flex-shrink-0 ansel_mx-auto"
                                        src={image.imageUrl}
                                        alt=""
                                    />
                                </div>
                                <div>
                                    <div className="ansel_-mt-px ansel_flex ansel_border-0 ansel_border-t ansel_border-gray-200 ansel_border-solid">
                                        <div className="ansel_w-0 ansel_flex-1 ansel_flex">
                                            <a
                                                href="#"
                                                className="ansel_relative ansel_-mr-px w-0 ansel_flex-1 ansel_inline-flex ansel_items-center ansel_justify-center ansel_py-4 ansel_text-sm ansel_text-gray-700 ansel_font-medium border ansel_border-transparent ansel_rounded-bl-lg hover:ansel_bg-gray-200"
                                            >
                                                <IconContext.Provider value={{ color: '#525252' }}>
                                                    <FiEdit className="ansel_w-5 ansel_h-5 ansel_text-gray-400" aria-hidden="true" />
                                                    <span className="ansel_sr-only">Edit Fields</span>
                                                </IconContext.Provider>
                                            </a>
                                        </div>
                                        <div className="ansel_-ml-px ansel_w-0 ansel_flex-1 ansel_flex ansel_border-0 ansel_border-l ansel_border-gray-200 ansel_border-solid">
                                            <a
                                                href="#"
                                                className="ansel_relative ansel_w-0 ansel_flex-1 ansel_inline-flex ansel_items-center ansel_justify-center ansel_py-4 ansel_text-sm ansel_text-gray-700 ansel_font-medium ansel_border ansel_border-transparent hover:ansel_text-gray-500 hover:ansel_bg-gray-200"
                                            >
                                                <IconContext.Provider value={{ color: '#525252' }}>
                                                    <ImCrop className="ansel_w-5 ansel_h-5 ansel_text-gray-400" aria-hidden="true" />
                                                    <span className="ansel_sr-only">Edit Image</span>
                                                </IconContext.Provider>
                                            </a>
                                        </div>
                                        <div className="ansel_-ml-px ansel_w-0 ansel_flex-1 ansel_flex ansel_border-0 ansel_border-l ansel_border-gray-200 ansel_border-solid">
                                            <a
                                                href="#"
                                                className="ansel_relative ansel_w-0 ansel_flex-1 ansel_inline-flex ansel_items-center ansel_justify-center ansel_py-4 ansel_text-sm ansel_text-gray-700 ansel_font-medium ansel_border ansel_border-transparent hover:ansel_text-gray-500 hover:ansel_bg-gray-200"
                                            >
                                                <IconContext.Provider value={{ color: '#525252' }}>
                                                    <CgEditBlackPoint className="ansel_w-5 ansel_h-5 ansel_text-gray-400" aria-hidden="true" />
                                                    <span className="ansel_sr-only">Edit Focal Point</span>
                                                </IconContext.Provider>
                                            </a>
                                        </div>
                                        <DeleteButton index={index} setFieldState={setFieldState} />
                                    </div>
                                </div>
                            </li>
                        ))}
                    </ul>
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
