import * as React from 'react';
import { createContext, useContext } from 'react';
import TranslationsType from './TranslationsType';

const TranslationsContext = createContext<TranslationsType>({
    imageUploadError: '',
    selectImageFromDevice: '',
    unusableImage: '',
    dimensionsNotMet: '',
    errorLoadingImage: '',
});

const useTranslations = () => {
    const context = useContext(TranslationsContext);

    if (!context) {
        throw new Error(
            'useTranslations must be used within a TranslationsProvider',
        );
    }

    return context;
};

const TranslationsProvider = (props) => <TranslationsContext.Provider
    value={props.translations}
    {...props}
/>;

export { TranslationsProvider, useTranslations };
