import * as React from 'react';
import { SortableHandle } from 'react-sortable-hoc';
import {
    ChangeEventHandler, MouseEventHandler,
} from 'react';
import Field from '../Field';

const RowHandler = SortableHandle(() => <a
    href="#"
    title="reorder row"
    style={{ display: 'block' }}
/>);

const Row = ({
    rowIndex,
    field,
    inputNameBase,
    deleteField,
    updateLabel,
    updateHandle,
    updateType,
    updateRequired,
}: {
    rowIndex: number,
    field: Field
    inputNameBase: string,
    deleteField: MouseEventHandler,
    updateLabel: ChangeEventHandler,
    updateHandle: ChangeEventHandler,
    updateType: ChangeEventHandler,
    updateRequired: ChangeEventHandler,
}) => (
    <div className="keyvalue-item-container">
        <div className="fields-keyvalue-item">
            <ul className="toolbar sort-cancel">
                <li className="reorder ui-sortable-handle">
                    <RowHandler />
                </li>
            </ul>
            <div className="field-control" style={{ width: '25%' }}>
                <input
                    onChange={updateLabel}
                    type="text"
                    name={`${inputNameBase}[${rowIndex}][label]`}
                    value={field.label}
                    data-index={rowIndex}
                />
            </div>
            <div className="field-control" style={{ width: '25%' }}>
                <input
                    onChange={updateHandle}
                    type="text"
                    name={`${inputNameBase}[${rowIndex}][handle]`}
                    value={field.handle}
                    data-index={rowIndex}
                />
            </div>
            <div className="field-control" style={{ width: '25%' }}>
                <select
                    onChange={updateType}
                    name={`${inputNameBase}[${rowIndex}][type]`}
                    data-index={rowIndex}
                    style={{ width: '100%' }}
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
            <div className="field-control" style={{ width: '25%' }}>
                <input
                    type="hidden"
                    name={`${inputNameBase}[${rowIndex}][required]`}
                    value=""
                />
                <label className="checkbox-label">
                    <input
                        onChange={updateRequired}
                        type="checkbox"
                        value="1"
                        name={`${inputNameBase}[${rowIndex}][required]`}
                        checked={field.required}
                        data-index={rowIndex}
                    />
                    <div className="checkbox-label__text">
                        <div>Require Field</div>
                    </div>
                </label>
            </div>
            <ul className="toolbar">
                <li className="remove">
                    <a
                        onClick={deleteField}
                        data-index={rowIndex}
                        href="#"
                        rel="remove_row"
                        title="remove row"
                        style={{ display: 'block' }}
                    />
                </li>
            </ul>
        </div>
    </div>
);

export default Row;
