import * as React from 'react';
import { XCircleIcon } from '@heroicons/react/solid';
import { useErrorMessages } from './ErrorMessagesContext';

const ErrorMessagesDisplay = () => {
    const { errorMessages } = useErrorMessages();

    return <>{
        Object.keys(errorMessages).map((errorKey) => (
            <div
                key={errorKey}
                className="ansel_bg-red-50 ansel_px-4 ansel_py-1.5 ansel_border-0 ansel_border-b ansel_border-red-100 ansel_border-solid"
            >
                <div className="ansel_flex">
                    <div className="ansel_flex-shrink-0 ansel_-mb-2">
                        <XCircleIcon
                            className="ansel_h-5 ansel_w-5 ansel_text-red-400"
                            aria-hidden="true"
                        />
                    </div>
                    <div className="ansel_ml-3">
                        <div className="ansel_text-sm ansel_font-medium ansel_text-red-800">
                            {errorMessages[errorKey]}
                        </div>
                    </div>
                </div>
            </div>
        ))
    }</>;
};

export default ErrorMessagesDisplay;
