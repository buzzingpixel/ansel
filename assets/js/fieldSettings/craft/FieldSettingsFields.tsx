import * as React from 'react';
import { useState } from 'react';
import Field from './Field';

const FieldSettingsFields = (
    { templateInput }: {templateInput: HTMLInputElement},
) => {
    const inputNameBase = templateInput.name;

    const [fields, setFields] = useState<Array<Field>>([]);

    const addField = () => {
        setFields([
            ...fields,
            new Field(),
        ]);
    };

    const deleteField = (e: React.MouseEvent<HTMLAnchorElement>) => {
        const newFields = fields.map((x) => x);

        newFields.splice(
            parseInt(e.currentTarget.dataset.index, 10),
            1,
        );

        setFields(newFields);
    };

    const updateLabel = (e: React.ChangeEvent<HTMLTextAreaElement>) => {
        const index = parseInt(e.currentTarget.dataset.index, 10);

        const newFields = fields.map((x) => x);

        newFields[index].label = e.currentTarget.value;

        setFields(newFields);
    };

    const updateHandle = (e: React.ChangeEvent<HTMLTextAreaElement>) => {
        const index = parseInt(e.currentTarget.dataset.index, 10);

        const newFields = fields.map((x) => x);

        newFields[index].handle = e.currentTarget.value;

        setFields(newFields);
    };

    const updateType = (e: React.ChangeEvent<HTMLSelectElement>) => {
        const index = parseInt(e.currentTarget.dataset.index, 10);

        const newFields = fields.map((x) => x);

        newFields[index].type = e.currentTarget.value;

        setFields(newFields);
    };

    const updateRequired = (e: React.ChangeEvent<HTMLInputElement>) => {
        const index = parseInt(e.currentTarget.dataset.index, 10);

        const newFields = fields.map((x) => x);

        newFields[index].required = e.currentTarget.checked;

        setFields(newFields);
    };

    return (
        <div className="field">
            <div className="heading"><label>Custom Fields</label></div>
            <div className="input ltr">
                <input
                    type="hidden"
                    name={`${inputNameBase}[]`}
                    value=""
                />
                <table className="editable fullwidth">
                    <thead>
                    <tr>
                        <th scope="col" className="singleline-cell textual">Label</th>
                        <th scope="col" className="singleline-cell textual">Handle</th>
                        <th scope="col" className="singleline-cell textual">Type</th>
                        <th scope="col" className="singleline-cell textual">Required</th>
                        <th colSpan={2} />
                    </tr>
                    </thead>
                    <tbody>
                    {fields.map((field, index) => (
                        <tr>
                            <td className="singleline-cell textual">
                                <textarea
                                    onChange={updateLabel}
                                    name={`${inputNameBase}[${index}][label]`}
                                    rows={1}
                                    style={{ minHeight: '34px', boxShadow: 'none' }}
                                    value={field.label}
                                    data-index={index}
                                    className='focus:outline-none'
                                />
                            </td>
                            <td className="code singleline-cell textual">
                                <textarea
                                    onChange={updateHandle}
                                    name={`${inputNameBase}[${index}][handle]`}
                                    rows={1}
                                    style={{ minHeight: '34px', boxShadow: 'none' }}
                                    value={field.handle}
                                    data-index={index}
                                />
                            </td>
                            <td className="singleline-cell textual">
                                <div className="input ltr p-2">
                                    <div className="select">
                                        <select
                                            onChange={updateType}
                                            name={`${inputNameBase}[${index}][type]`}
                                            data-index={index}
                                        >
                                            <option
                                                value="text"
                                                selected={field.type === 'text'}
                                            >
                                                Text
                                            </option>
                                            <option
                                                value="bool"
                                                selected={field.type === 'bool'}
                                            >
                                                Light Switch
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td className="thin checkbox-cell">
                                <div className="checkbox-wrapper">
                                    <input
                                        type="hidden"
                                        name={`${inputNameBase}[${index}][required]`}
                                        value=""
                                    />
                                    <input
                                        onChange={updateRequired}
                                        id={`checkbox${index}`}
                                        type="checkbox"
                                        className="checkbox"
                                        name={`${inputNameBase}[${index}][required]`}
                                        value="1"
                                        checked={field.required}
                                        data-index={index}
                                    />
                                    <label htmlFor={`checkbox${index}`}/>
                                </div>
                            </td>
                            <td className="thin action">
                                <a
                                    className="move icon"
                                    title="Reorder"
                                    aria-label="Reorder"
                                />
                            </td>
                            <td
                                className="thin action"
                            >
                                <a
                                    onClick={deleteField}
                                    data-index={index}
                                    className="delete icon"
                                    title="Delete"
                                    aria-label="Delete"
                                />
                            </td>
                        </tr>
                    ))}
                    </tbody>
                </table>
                <button
                    onClick={addField}
                    type="button"
                    className="btn add icon"
                    tabIndex={0}
                    style={{ opacity: 1, pointerEvents: 'auto' }}
                >
                    Add a field
                </button>
            </div>
        </div>
    );
};

export default FieldSettingsFields;
