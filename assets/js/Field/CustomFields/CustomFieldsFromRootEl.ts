import CustomFieldType from './CustomFieldType';

const CustomFieldsFromRootEl = (
    anselFieldEl: HTMLDivElement,
): Array<CustomFieldType> => {
    const customFieldsElement = anselFieldEl.getElementsByClassName(
        'ansel_field_custom_fields',
    ).item(0) as HTMLSpanElement;

    return JSON.parse(
        customFieldsElement.dataset.json,
    ) as Array<CustomFieldType>;
};

export default CustomFieldsFromRootEl;
