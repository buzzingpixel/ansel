import * as React from 'react';
import { SortableHandle } from 'react-sortable-hoc';
import styled from 'styled-components';
import {
    ChangeEventHandler, MouseEventHandler,
} from 'react';
import Field from './Field';

const TrWrapper = styled.tr`
  .firstElement {
    display: flex;
    flex-direction: row;
  }

  &.helperContainerClass {
    width: auto;
    border: 1px solid #efefef;
    box-shadow: 0 5px 5px -5px rgba(0, 0, 0, 0.2);
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 3px;

    & > td {
      padding: 5px;
      width: 200px;
    }
  }
`;

const RowHandler = SortableHandle(() => <td className="thin action">
    <a
        className="move icon handle"
        title="Reorder"
        aria-label="Reorder"
    />
</td>);

const TableRow = ({
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
        <TrWrapper>
            <td className="singleline-cell textual">
                    <textarea
                        onChange={updateLabel}
                        name={`${inputNameBase}[${rowIndex}][label]`}
                        rows={1}
                        style={{ minHeight: '34px', boxShadow: 'none' }}
                        value={field.label}
                        data-index={rowIndex}
                        className='focus:outline-none'
                    />
            </td>
            <td className="code singleline-cell textual">
                    <textarea
                        onChange={updateHandle}
                        name={`${inputNameBase}[${rowIndex}][handle]`}
                        rows={1}
                        style={{ minHeight: '34px', boxShadow: 'none' }}
                        value={field.handle}
                        data-index={rowIndex}
                    />
            </td>
            <td className="singleline-cell textual">
                <div className="input ltr p-2">
                    <div className="select">
                        <select
                            onChange={updateType}
                            name={`${inputNameBase}[${rowIndex}][type]`}
                            data-index={rowIndex}
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
                <div className="checkbox-wrapper" style={{ width: '40px' }}>
                    <input
                        type="hidden"
                        name={`${inputNameBase}[${rowIndex}][required]`}
                        value=""
                    />
                    <input
                        onChange={updateRequired}
                        id={`checkbox${rowIndex}`}
                        type="checkbox"
                        className="checkbox"
                        name={`${inputNameBase}[${rowIndex}][required]`}
                        value="1"
                        checked={field.required}
                        data-index={rowIndex}
                    />
                    <label htmlFor={`checkbox${rowIndex}`}/>
                </div>
            </td>
            <RowHandler/>
            <td
                className="thin action"
            >
                <a
                    onClick={deleteField}
                    data-index={rowIndex}
                    className="delete icon"
                    title="Delete"
                    aria-label="Delete"
                />
            </td>
        </TrWrapper>
);

export default TableRow;
