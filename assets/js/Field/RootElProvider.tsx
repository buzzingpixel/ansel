import * as React from 'react';
import { createContext, useContext } from 'react';

const RootElContext = createContext<HTMLDivElement>(undefined);

const useRootEl = () => {
    const context = useContext(RootElContext);

    if (!context) {
        throw new Error(
            'useRootEl must be used within a RootElProvider',
        );
    }

    return context;
};

const RootElProvider = (props) => <RootElContext.Provider
    value={props.rootEl}
    {...props}
/>;

export { RootElProvider, useRootEl };
