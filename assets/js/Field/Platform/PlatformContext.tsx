import * as React from 'react';
import { createContext, useContext } from 'react';
import PlatformType from './PlatformType';

const PlatformContext = createContext<PlatformType>({
    environment: '',
});

const usePlatform = () => {
    const context = useContext(PlatformContext);

    if (!context) {
        throw new Error(
            'usePlatform must be used within a PlatformProvider',
        );
    }

    return context;
};

const PlatformProvider = (props) => <PlatformContext.Provider
    value={props.platform}
    {...props}
/>;

export { PlatformProvider, usePlatform };
