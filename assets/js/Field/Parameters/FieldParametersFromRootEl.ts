import FieldParametersType from './FieldParametersType';

const FieldParametersFromRootEl = (
    anselFieldEl: HTMLDivElement,
): FieldParametersType => {
    const parametersElement = anselFieldEl.getElementsByClassName(
        'ansel_field_parameters',
    ).item(0) as HTMLSpanElement;

    return JSON.parse(
        parametersElement.dataset.json,
    ) as FieldParametersType;
};

export default FieldParametersFromRootEl;
