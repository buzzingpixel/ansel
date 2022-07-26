import PlatformType from './PlatformType';

const PlatformFromRootEl = (
    anselFieldEl: HTMLDivElement,
): PlatformType => {
    const platformElement = anselFieldEl.getElementsByClassName(
        'ansel_platform',
    ).item(0) as HTMLSpanElement;

    return JSON.parse(
        platformElement.dataset.json,
    ) as PlatformType;
};

export default PlatformFromRootEl;
