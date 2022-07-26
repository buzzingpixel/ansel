const InputPlaceholderFromRootEl = (
    anselFieldEl: HTMLDivElement,
): HTMLInputElement => anselFieldEl.getElementsByClassName(
    'ansel_field_input_placeholder',
).item(0) as HTMLInputElement;

export default InputPlaceholderFromRootEl;
