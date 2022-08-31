import * as React from 'react';
import { MdClose } from 'react-icons/md';
import { BsCheck, BsChevronLeft, BsChevronRight } from 'react-icons/bs';
import { Portal } from 'react-portal';
import { useEffect } from 'react';

interface ActionInterface {
    id: string;
    action: (event: Event|React.MouseEvent) => void;
    prependClasses?: string;
    icon: | React.ReactChild | React.ReactChild[];
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
    prevActionDisabled,
    cancelAction,
    acceptAction,
    nextAction,
    nextActionDisabled,
    children,
}: {
    heading?: string,
    prevAction?: (event: Event|React.MouseEvent) => void,
    prevActionDisabled?: boolean,
    cancelAction?: (event: Event|React.MouseEvent) => void,
    acceptAction?: (event: Event|React.MouseEvent) => void,
    nextAction?: (event: Event|React.MouseEvent) => void,
    nextActionDisabled?: boolean,
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
        let prevActionClasses = 'ansel_text-gray-500 hover:ansel_text-gray-500';

        if (prevActionDisabled) {
            prevActionClasses = 'ansel_bg-gray-300 hover:ansel_bg-gray-300 hover:ansel_cursor-default ansel_text-gray-400 hover:ansel_text-gray-400';
        }

        actions.push({
            id: 'portal-prev-action',
            action: prevAction,
            prependClasses: prevActionClasses,
            icon: <BsChevronLeft size="22px" />,
        });
    }

    if (cancelAction) {
        actions.push({
            id: 'portal-cancel-action',
            action: cancelAction,
            prependClasses: 'ansel_text-red-600 hover:ansel_text-red-600',
            icon: <MdClose size="22px" />,
        });
    }

    if (acceptAction) {
        actions.push({
            id: 'portal-accept-action',
            action: acceptAction,
            prependClasses: 'ansel_text-green-500 hover:ansel_text-green-500',
            icon: <BsCheck size="30px" />,
        });
    }

    if (nextAction) {
        let nextActionClasses = 'ansel_text-gray-500 hover:ansel_text-gray-500';

        if (nextActionDisabled) {
            nextActionClasses = 'ansel_bg-gray-300 hover:ansel_bg-gray-300 hover:ansel_cursor-default ansel_text-gray-400 hover:ansel_text-gray-400';
        }

        actions.push({
            id: 'portal-next-action',
            action: nextAction,
            prependClasses: nextActionClasses,
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
                            {actions.map(({
                                id: actionId,
                                action,
                                prependClasses,
                                icon,
                            }, index) => {
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
                                        key={actionId}
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
