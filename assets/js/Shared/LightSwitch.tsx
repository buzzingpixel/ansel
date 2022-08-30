import * as React from 'react';
import { useState } from 'react';
import { Switch } from '@headlessui/react';
import { usePlatform } from '../Field/Platform/PlatformContext';

function classNames (...classes) {
    return classes.filter(Boolean).join(' ');
}

const LightSwitch = ({
    checked,
    onChange,
}: {
    checked?: boolean,
    onChange?: (isChecked: boolean) => void,
}) => {
    const { environment } = usePlatform();

    const [checkedState, setCheckedState] = useState(
        checked || false,
    );

    const changeHandler = (isChecked: boolean) => {
        setCheckedState(isChecked);

        if (!onChange) {
            return;
        }

        onChange(isChecked);
    };

    return (
        <Switch
            checked={checkedState}
            onChange={changeHandler}
            className={classNames(
                checkedState ? 'ansel_bg-green-500' : 'ansel_bg-gray-200',
                'ansel_relative ansel_inline-flex ansel_flex-shrink-0 ansel_h-24px ansel_w-44px ansel_border-2 ansel_border-transparent ansel_rounded-full ansel_cursor-pointer ansel_transition-colors ansel_ease-in-out ansel_duration-200 focus:ansel_outline-none focus:ansel_ring-2 focus:ansel_ring-offset-2 focus:ansel_ring-green-500 ansel_font-size-16px ansel_p-0 ansel_line-height-24px ansel_items-center',
                // This is so gross
                environment === 'craft' ? 'ansel_pl-2px' : '',
            )}
        >
            <span
                className={classNames(
                    checkedState ? 'ansel_translate-x-20px' : 'ansel_translate-x-0',
                    'ansel_pointer-events-none ansel_relative ansel_inline-block ansel_h-20px ansel_w-20px ansel_rounded-full ansel_bg-white ansel_shadow ansel_transform ansel_ring-0 ansel_transition ansel_ease-in-out ansel_duration-200',
                )}
            >
                <span
                    className={classNames(
                        checkedState ? 'ansel_opacity-0 ansel_ease-out ansel_duration-100' : 'ansel_opacity-100 ansel_ease-in ansel_duration-200',
                        'ansel_absolute ansel_inset-0 ansel_h-full ansel_w-full ansel_flex ansel_items-center ansel_justify-center ansel_transition-opacity',
                    )}
                    aria-hidden="true"
                >
                    <svg className="ansel_h-12px ansel_w-12px ansel_text-gray-400" fill="none" viewBox="0 0 12 12">
                        <path
                            d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2"
                            stroke="currentColor"
                            strokeWidth={2}
                            strokeLinecap="round"
                            strokeLinejoin="round"
                        />
                    </svg>
                </span>
                <span
                    className={classNames(
                        checkedState ? 'ansel_opacity-100 ansel_ease-in ansel_duration-200' : 'ansel_opacity-0 ansel_ease-out ansel_duration-100',
                        'ansel_absolute ansel_inset-0 ansel_h-full ansel_w-full ansel_flex ansel_items-center ansel_justify-center ansel_transition-opacity',
                    )}
                    aria-hidden="true"
                >
                    <svg className="ansel_h-12px ansel_w-12px ansel_text-green-500" fill="currentColor" viewBox="0 0 12 12">
                        <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                    </svg>
                </span>
            </span>
        </Switch>
    );
};

export default LightSwitch;
