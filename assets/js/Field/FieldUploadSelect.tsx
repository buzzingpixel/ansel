import * as React from 'react';
import { ImPlus } from 'react-icons/im';
import { IconContext } from 'react-icons';

const FieldUploadSelect = (
    {
        dropZoneOpenDeviceDialog,
    }: {
        dropZoneOpenDeviceDialog?: () => void | null,
    },
) => {
    const openCmsDialog = (e: React.MouseEvent) => {
        e.preventDefault();

        console.log('openCmsDialog');
    };

    const openDeviceDialog = (e: React.MouseEvent) => {
        e.preventDefault();

        dropZoneOpenDeviceDialog();
    };

    return (
        <>
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
                                {/* TODO: Get this from lang */}
                                <span className="ansel_inline-block ansel_mx-auto ansel_align-middle">
                                    Select an image to upload from your device
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
