import * as React from 'react';
import * as ReactDOM from 'react-dom';
import SelectDropdown from './SelectDropdown';

export default (container: Element) => {
    const options = [];

    const selectEl = container.getElementsByTagName(
        'select',
    ).item(0);

    const optionEls = selectEl.getElementsByTagName('option');

    let initialValue = null;

    for (let i = 0; i < optionEls.length; i += 1) {
        const optionEl = optionEls[i] as HTMLOptionElement;

        if (selectEl.value === optionEl.value) {
            initialValue = {
                label: optionEl.innerText.trim(),
                value: optionEl.value,
            };
        }

        options.push({
            label: optionEl.innerText.trim(),
            value: optionEl.value,
        });
    }

    ReactDOM.render(
        <SelectDropdown
            name={selectEl.name}
            initialValue={initialValue}
            options={options}
        />,
        container,
    );
};
