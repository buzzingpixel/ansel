import * as React from 'react';
import { useEffect, useRef } from 'react';
import { IconContext } from 'react-icons';
import { ImPlus } from 'react-icons/im';
import { usePlatform } from '../Platform/PlatformContext';
import EeFileType from '../Platform/EeFileType';
import { useTranslations } from '../Translations/TranslationsContext';
import { useImages } from '../FieldState/Images/ImagesContext';
import CraftFileType from '../Platform/CraftFileType';
import useSelectedFileHandler from './SelectedFileHandler';
import { useFieldSettings } from '../FieldSettings/FieldSettingsContext';
import { useErrorMessages } from '../FieldState/ErrorMessages/ErrorMessagesContext';

const Uploading = (
    { openDropZoneDeviceDialog }: { openDropZoneDeviceDialog: () => void },
) => {
    const {
        selectImageFromDevice,
        limitedToXImages,
        selectExistingImage,
        dragImagesToUpload,
    } = useTranslations();

    const {
        selectedFileHandlerEe,
        selectedFileHandlerCraft,
    } = useSelectedFileHandler();

    const {
        environment,
        fileChooserModalLink,
        uploadLocationFolderId,
    } = usePlatform();

    const buttonRef = useRef(document.createElement('div'));

    const { images, hasImages } = useImages();

    const { addErrorMessage } = useErrorMessages();

    const settings = useFieldSettings();

    let anchor = null;

    useEffect(() => {
        if (environment !== 'ee') {
            return;
        }

        const $button = $(buttonRef.current);

        const html = document.createElement('html');
        html.innerHTML = window.atob(fileChooserModalLink);
        anchor = html.getElementsByTagName('a').item(0);

        const $anchor = $(anchor);

        // eslint-disable-next-line @typescript-eslint/ban-ts-comment
        // @ts-ignore
        $anchor.FilePicker({
            callback: (file: EeFileType, references) => {
                references.modal.find('.m-close').click();

                if (
                    settings.maxQty > 0
                    && settings.preventUploadOverMax
                    && images.length >= settings.maxQty
                ) {
                    addErrorMessage(limitedToXImages);

                    return;
                }

                selectedFileHandlerEe(file);
            },
        });

        $button.append($anchor);
    });

    const openCmsDialog = (e: React.MouseEvent) => {
        e.preventDefault();

        // eslint-disable-next-line default-case
        switch (environment) {
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
                        `folder:${uploadLocationFolderId}`,
                    ],
                    onSelect (files: Array<CraftFileType>) {
                        const projectedTotal = images.length + files.length;

                        if (
                            settings.maxQty > 0
                            && settings.preventUploadOverMax
                            && projectedTotal > settings.maxQty
                        ) {
                            addErrorMessage(limitedToXImages);

                            const deleteCount = projectedTotal - settings.maxQty;

                            files.splice(0, deleteCount);
                        }

                        $('.modal-shade').remove();

                        modal.destroy();

                        files.forEach((file) => {
                            selectedFileHandlerCraft(file);
                        });
                    },
                });
        }
    };

    const openDeviceDialog = (e: React.MouseEvent) => {
        e.preventDefault();

        openDropZoneDeviceDialog();
    };

    const paddingClass = hasImages ? 'ansel_pb-4' : '';

    return (
        <div className={paddingClass}>
            <div className="ansel_hidden" ref={buttonRef} />
            <div className="ansel_text-gray-700 ansel_italic ansel_text-center ansel_pb-4">
                {dragImagesToUpload}
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
                            {selectExistingImage}
                        </span>
                    </IconContext.Provider>
                </a>
            </div>
            {openDropZoneDeviceDialog !== null
                && <>
                    {/* TODO: Lang */}
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
                                    {selectImageFromDevice}
                                </span>
                            </IconContext.Provider>
                        </a>
                    </div>
                </>
            }
        </div>
    );
};

export default Uploading;
