import * as React from 'react';
import { MdClose } from 'react-icons/md';
import { BsCheck, BsChevronLeft, BsChevronRight } from 'react-icons/bs';
import { Portal } from 'react-portal';
import { useEffect } from 'react';
import { v4 as uuid } from 'uuid';

interface ActionInterface {
    action: (event: Event|React.MouseEvent) => void,
    prependClasses?: string,
    icon: | React.ReactChild | React.ReactChild[],
}

const iconAnchorClasses = 'ansel_bg-gray-100 hover:ansel_bg-gray-200 ansel_h-40px ansel_w-70px ansel_flex ansel_flex-row ansel_items-center ansel_justify-center';

const RenderHeading = ({ heading }: {heading?: string}) => {
    if (!heading) {
        return <></>;
    }

    return (
        <div className="ansel_text-gray-200 ansel_text-center ansel_p-2">
            {heading}
        </div>
    );
};

const AnselPortal = ({
    heading,
    prevAction,
    cancelAction,
    acceptAction,
    nextAction,
    children,
}: {
    heading?: string,
    prevAction?: (event: Event|React.MouseEvent) => void,
    cancelAction?: (event: Event|React.MouseEvent) => void,
    acceptAction?: (event: Event|React.MouseEvent) => void,
    nextAction?: (event: Event|React.MouseEvent) => void,
    children?:
        | React.ReactChild
        | React.ReactChild[],
}) => {
    useEffect(() => {
        const handleKeyDown = (event: KeyboardEvent) => {
            const { code } = event;

            if (prevAction && code === 'ArrowLeft') {
                prevAction(event);
            }

            if (cancelAction && code === 'Escape') {
                cancelAction(event);

                return;
            }

            if (acceptAction && (code === 'Enter' || code === 'NumpadEnter')) {
                acceptAction(event);

                return;
            }

            if (nextAction && code === 'ArrowRight') {
                nextAction(event);
            }
        };

        window.addEventListener('keydown', handleKeyDown);

        return () => {
            window.removeEventListener('keydown', handleKeyDown);
        };
    });

    const actions = [] as Array<ActionInterface>;

    if (prevAction) {
        actions.push({
            action: prevAction,
            prependClasses: 'ansel_text-gray-500 hover:ansel_text-gray-500',
            icon: <BsChevronLeft size="22px" />,
        });
    }

    if (cancelAction) {
        actions.push({
            action: cancelAction,
            prependClasses: 'ansel_text-red-600 hover:ansel_text-red-600',
            icon: <MdClose size="22px" />,
        });
    }

    if (acceptAction) {
        actions.push({
            action: acceptAction,
            prependClasses: 'ansel_text-green-500 hover:ansel_text-green-500',
            icon: <BsCheck size="30px" />,
        });
    }

    if (nextAction) {
        actions.push({
            action: nextAction,
            prependClasses: 'ansel_text-gray-500 hover:ansel_text-gray-500',
            icon: <BsChevronRight size="22px" />,
        });
    }

    return (
        <Portal>
            <div className="ansel_box-border ansel_fixed ansel_top-0 ansel_left-0 ansel_w-full ansel_h-full ansel_z-max ansel_bg-black-opacity-60">
                <div className="ansel_absolute ansel_top-0 ansel_left-0 ansel_w-full ansel_h-full ansel_flex ansel_items-center ansel_justify-center">
                    <div>
                        <div className="ansel_relative">
                            <RenderHeading heading={heading} />
                            <div className="ansel_text-center">
                                {children}
                            </div>
                        </div>
                        <div className="ansel_flex ansel_flex-row ansel_items-center ansel_justify-center ansel_pb-2">
                            {actions.map(({ action, prependClasses, icon }, index) => {
                                prependClasses = prependClasses || '';

                                if (index === 0) {
                                    prependClasses += ' ansel_rounded-l-lg';
                                }

                                if (index === (actions.length - 1)) {
                                    prependClasses += ' ansel_rounded-r-lg';
                                }

                                const classes = `${prependClasses} ${iconAnchorClasses}`;

                                const isNotFirst = index > 0;

                                return (
                                    <div
                                        key={uuid()}
                                        className={classes}
                                    >
                                        { isNotFirst && <div className="ansel_bg-gray-300 ansel_h-40px ansel_w-1px" /> }
                                        <a
                                            onClick={action}
                                            href="#0"
                                            className={classes}
                                        >
                                            {icon}
                                        </a>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </div>
            </div>
        </Portal>
    );
};

export default AnselPortal;
