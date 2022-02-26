import * as React from 'react';
import { useState, useCallback, useRef } from 'react';
// @see https://codesandbox.io/s/table-draggable-rows-137ku?file=/src/TableRow.js
import { SortableContainer, SortableElement, SortStart } from 'react-sortable-hoc';
import TableRow from './TableRow';
import ArrayMove from '../../Utility/ArrayMove';
import Field from '../Field';

const SortableCont = SortableContainer(
    ({ children }) => <tbody>{children}</tbody>,
);

const SortableItem = SortableElement(
    (props) => <TableRow {...props} />,
);

type InitialField = {
    label: string,
    handle: string,
    type: string,
    required: boolean,
}

const FieldSettingsFields = (
    {
        templateInput,
        existingFields,
    }: {
        templateInput: HTMLInputElement,
        existingFields: Array<InitialField>,
    },
) => {
    const initialExistingFields = [];

    existingFields.forEach((field) => {
        initialExistingFields.push(new Field(field));
    });

    const inputNameBase = templateInput.name;

    const tableWrapperRef = useRef(document.createElement('div'));

    const [fields, setFields] = useState<Array<Field>>(
        initialExistingFields,
    );

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

    const onSortStart = useCallback((event: SortStart) => {
        const table = document.createElement('table');

        const tbody = document.createElement('tbody');

        table.appendChild(tbody);

        table.classList.add('editable');

        table.classList.add('fullwidth');

        table.classList.add('sort-helper');

        tbody.appendChild(event.helper);

        tableWrapperRef.current.appendChild(table);
    }, []);

    const onSortEnd = useCallback(({ oldIndex, newIndex }) => {
        // eslint-disable-next-line arrow-body-style
        setFields((oldFields) => {
            return ArrayMove(oldFields, oldIndex, newIndex);
        });

        const sortHelpers = document.getElementsByClassName(
            'sort-helper',
        );

        for (let i = sortHelpers.length - 1; i >= 0; i -= 1) {
            // Remove first element (at [0]) repeatedly
            sortHelpers[0].parentNode.removeChild(sortHelpers[0]);
        }
    }, []);

    return (
        <div className="field">
            <div className="heading"><label>Custom Fields</label></div>
            <div className="input ltr" ref={tableWrapperRef}>
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
                    <SortableCont
                        onSortEnd={onSortEnd}
                        onSortStart={onSortStart}
                        axis="y"
                        lockAxis="y"
                        lockToContainerEdges={true}
                        lockOffset={['30%', '50%']}
                        helperClass="helperContainerClass"
                        useDragHandle={true}
                    >
                        {fields.map((field, index) => (
                            <SortableItem
                                key={`item-${index}`}
                                index={index}
                                rowIndex={index}
                                field={field}
                                inputNameBase={inputNameBase}
                                deleteField={deleteField}
                                updateLabel={updateLabel}
                                updateHandle={updateHandle}
                                updateType={updateType}
                                updateRequired={updateRequired}
                            />
                        ))}
                    </SortableCont>
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
