import * as React from 'react';
import {
    createContext, useContext, useEffect, useState,
} from 'react';
import KeyboardType from './KeyboardType';

const KeyboardContext = createContext<KeyboardType>(undefined);

const useKeyboard = () => {
    const context = useContext(KeyboardContext);

    if (!context) {
        throw new Error(
            'useKeyboard must be used within a KeyboardProvider',
        );
    }

    return context;
};

const KeyboardProvider = (props) => {
    const [keyCode, setKeyCode] = useState<string>('');

    const [altDown, setAltDown] = useState<boolean>(false);

    const [hasOneClick, setHasOneClick] = useState<string>('');

    const [doubleKeyCode, setDoubleKeyCode] = useState<string>('');

    const [altDoubleClickActive, setAltDoubleClickActive] = useState<boolean>(
        false,
    );

    const handleKeyDown = (event: KeyboardEvent) => {
        const { code } = event;

        if (code === 'AltRight' || code === 'AltLeft') {
            setAltDown(true);
        }

        if (!hasOneClick) {
            setTimeout(() => {
                setHasOneClick('');
            }, 400);
        }

        setKeyCode(code);

        if (hasOneClick === code) {
            if (code === 'AltRight' || code === 'AltLeft') {
                setAltDoubleClickActive((oldDCA) => !oldDCA);
            }

            setDoubleKeyCode((oldDKC) => (oldDKC ? '' : code));
        }

        setHasOneClick(code);
    };

    const handleKeyUp = () => {
        setKeyCode('');
        setAltDown(false);
    };

    useEffect(
        () => {
            window.addEventListener('keydown', handleKeyDown);

            window.addEventListener('keyup', handleKeyUp);

            return () => {
                window.removeEventListener('keydown', handleKeyDown);

                window.removeEventListener('keyup', handleKeyUp);
            };
        },
    );

    return <KeyboardContext.Provider
        value={{
            keyCode,
            altDown,
            doubleKeyCode,
            altDoubleClickActive,
        }}
        children={props.children}
    />;
};

export { KeyboardProvider, useKeyboard };
