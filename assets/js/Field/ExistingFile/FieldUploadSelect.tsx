import * as React from 'react';
import { ImPlus } from 'react-icons/im';
import { IconContext } from 'react-icons';
import { useRef, useEffect } from 'react';
import EeFileType from './EeFileType';
import SelectedFileHandlerEe from './SelectedFileHandlerEe';
import SelectedFileHandlerCraft from './SelectedFileHandlerCraft';
import FieldDataType from '../Types/FieldDataType';
import FieldStateType from '../Types/FieldStateType';

const FieldUploadSelect = (
    {
        dropZoneOpenDeviceDialog,
        setFieldState,
        fieldData,
    }: {
        dropZoneOpenDeviceDialog?: () => void | null,
        setFieldState: CallableFunction,
        fieldData: FieldDataType,
    },
) => {
    const buttonRef = useRef(document.createElement('div'));

    let anchor = null;

    useEffect(() => {
        if (fieldData.platform.environment === 'ee') {
            const $button = $(buttonRef.current);

            const html = document.createElement('html');
            html.innerHTML = atob(fieldData.platform.fileChooserModalLink);
            anchor = html.getElementsByTagName('a')
                .item(0);

            const $anchor = $(anchor);

            // eslint-disable-next-line @typescript-eslint/ban-ts-comment
            // @ts-ignore
            $anchor.FilePicker({
                callback: (file: EeFileType, references) => {
                    references.modal.find('.m-close').click();

                    SelectedFileHandlerEe(
                        file,
                        setFieldState,
                        fieldData,
                    );
                },
            });

            $button.append($anchor);
        }
    });

    const openCmsDialog = (e: React.MouseEvent) => {
        e.preventDefault();

        setFieldState((prevState: FieldStateType) => {
            prevState.processes += 1;

            return { ...prevState };
        });

        // eslint-disable-next-line default-case
        switch (fieldData.platform.environment) {
            case 'ee':
                anchor.click();
                return;
            case 'craft':
                // eslint-disable-next-line no-case-declarations
                let modal = null;

                // eslint-disable-next-line @typescript-eslint/ban-ts-comment
                // @ts-ignore
                modal = window.Craft.createElementSelectorModal('craft\\elements\\Asset', {
                    criteria: {
                        kind: 'image',
                    },
                    multiSelect: true,
                    sources: [
                        `folder:${fieldData.platform.uploadLocationFolderId}`,
                    ],
                    onSelect (files) {
                        $('.modal-shade').remove();

                        modal.destroy();

                        files.forEach((file) => {
                            SelectedFileHandlerCraft(
                                file,
                                setFieldState,
                                fieldData,
                            );
                        });
                    },
                });
        }
    };

    const openDeviceDialog = (e: React.MouseEvent) => {
        e.preventDefault();

        dropZoneOpenDeviceDialog();
    };

    return (
        <>
            <div className="ansel_hidden" ref={buttonRef} />
            <div className="ansel_text-gray-700 ansel_italic ansel_text-center ansel_pb-4">
                Drag images here to upload
            </div>
            <div className="ansel_text-gray-700 ansel_italic ansel_text-center">
                <a
                    onClick={openCmsDialog}
                    href="#"
                    className="ansel_border ansel_border-dashed ansel_border-gray-300 ansel_inline-block ansel_mx-auto ansel_py-0.5 ansel_px-1.5 ansel_not-italic ansel_text-gray-700 hover:ansel_text-gray-700 hover:ansel_bg-gray-200"
                >
                    <IconContext.Provider value={{ color: '#525252' }}>
                        <span
                            className="ansel_inline-block ansel_mx-auto ansel_align-middle ansel_pr-1"
                            style={{ height: '18px' }}
                        >
                            <ImPlus/>
                        </span>
                        <span className="ansel_inline-block ansel_mx-auto ansel_align-middle">
                            Select existing image
                        </span>
                    </IconContext.Provider>
                </a>
            </div>
            {dropZoneOpenDeviceDialog !== null
                && <>
                    <div className="ansel_italic ansel_py-2 ansel_text-center">or</div>
                    <div className="ansel_text-gray-700 ansel_italic ansel_text-center">
                        <a
                            onClick={openDeviceDialog}
                            href="#"
                            className="ansel_border ansel_border-dashed ansel_border-gray-300 ansel_inline-block ansel_mx-auto ansel_py-0.5 ansel_px-1.5 ansel_not-italic ansel_text-gray-700 hover:ansel_text-gray-700 hover:ansel_bg-gray-200"
                        >
                            <IconContext.Provider value={{ color: '#525252' }}>
                                <span
                                    className="ansel_inline-block ansel_mx-auto ansel_align-middle ansel_pr-1"
                                    style={{ height: '18px' }}
                                >
                                    <ImPlus />
                                </span>
                                <span className="ansel_inline-block ansel_mx-auto ansel_align-middle">
                                    {fieldData.translations.selectImageFromDevice}
                                </span>
                            </IconContext.Provider>
                        </a>
                    </div>
                </>
            }
        </>
    );
};

export default FieldUploadSelect;
