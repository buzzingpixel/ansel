import * as React from 'react';
import { useState } from 'react';
import Select from 'react-select';

const SelectDropdown = ({
    name,
    initialValue,
    options,
}: {
    name: string,
    initialValue: {
        label: string,
        value: string
    },
    options: Array<{
        label: string,
        value: string
    }>
}) => {
    const [selectedOption, setSelectedOption] = useState(initialValue);

    const change = (option) => {
        setSelectedOption(option);
    };

    return <Select
        name={name}
        // eslint-disable-next-line @typescript-eslint/ban-ts-comment
        // @ts-ignore
        options={options}
        value={selectedOption}
        defaultValue={selectedOption}
        onChange={change}
    />;
};

export default SelectDropdown;
