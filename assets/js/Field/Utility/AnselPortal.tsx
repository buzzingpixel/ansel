import * as React from 'react';
import { MdClose } from 'react-icons/md';
import { BsCheck } from 'react-icons/bs';
import { Portal } from 'react-portal';
import { useEffect } from 'react';

const AnselPortal = ({
    accept,
    cancel,
    children,
}: {
    accept: (event: Event|React.MouseEvent) => void,
    cancel: (event: Event|React.MouseEvent) => void,
    children?:
        | React.ReactChild
        | React.ReactChild[],
}) => {
    const iconAnchorClasses = 'ansel_text-red-600 ansel_bg-gray-100 hover:ansel_bg-gray-200 ansel_h-40px ansel_w-70px ansel_flex ansel_flex-row ansel_items-center ansel_justify-center';

    useEffect(() => {
        const handleKeyDown = (event: KeyboardEvent) => {
            if (event.code === 'Escape') {
                cancel(event);

                return;
            }

            if (event.code === 'Enter' || event.code === 'NumpadEnter') {
                accept(event);
            }
        };

        window.addEventListener('keydown', handleKeyDown);

        return () => {
            window.removeEventListener('keydown', handleKeyDown);
        };
    });

    return (
        <Portal>
            <div className="ansel_fixed ansel_top-0 ansel_left-0 ansel_w-full ansel_h-full ansel_z-max ansel_bg-black-opacity-60">
                <div className="ansel_absolute ansel_top-0 ansel_left-0 ansel_w-full ansel_h-full ansel_flex ansel_items-center ansel_justify-center">
                    <div>
                        <div className="ansel_relative">
                            <div>
                                {children}
                            </div>
                        </div>
                        <div className="ansel_flex ansel_flex-row ansel_items-center ansel_justify-center">
                            <a
                                onClick={cancel}
                                href="#0"
                                className={`ansel_text-red-600 hover:ansel_text-red-600 ansel_rounded-l-lg ${iconAnchorClasses}`}
                            >
                                <MdClose size="22px" />
                            </a>
                            <div className="ansel_bg-gray-300 ansel_h-40px ansel_w-1px"></div>
                            <a
                                onClick={accept}
                                href="#0"
                                className={`ansel_text-green-500 hover:ansel_text-green-500 ansel_rounded-r-lg ${iconAnchorClasses}`}
                            >
                                <BsCheck size="30px" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </Portal>
    );
};

export default AnselPortal;
