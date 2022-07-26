import TranslationsType from './TranslationsType';

const TranslationsFromRootEl = (
    anselFieldEl: HTMLDivElement,
): TranslationsType => {
    const translationsElement = anselFieldEl.getElementsByClassName(
        'ansel_translations',
    ).item(0) as HTMLSpanElement;

    return JSON.parse(
        translationsElement.dataset.json,
    ) as TranslationsType;
};

export default TranslationsFromRootEl;
