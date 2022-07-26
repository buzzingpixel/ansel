import FieldSettingsType from './FieldSettingsType';

const FieldSettingsFromRootEl = (
    anselFieldEl: HTMLDivElement,
): FieldSettingsType => {
    const fieldSettingsElement = anselFieldEl.getElementsByClassName(
        'ansel_field_settings',
    ).item(0) as HTMLSpanElement;

    return JSON.parse(
        fieldSettingsElement.dataset.json,
    ) as FieldSettingsType;
};

export default FieldSettingsFromRootEl;
