import * as React from 'react';
import { useCallback, useState } from 'react';
// @see https://codesandbox.io/s/table-draggable-rows-137ku?file=/src/TableRow.js
import { SortableContainer, SortableElement } from 'react-sortable-hoc';
import Field from '../Field';
import Row from './Row';
import ArrayMove from '../../Utility/ArrayMove';

const SortableCont = SortableContainer(
    ({ children }) => <div>{children}</div>,
);

const SortableItem = SortableElement(
    (props) => <Row {...props} />,
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

    const [fields, setFields] = useState<Array<Field>>(
        initialExistingFields,
    );

    const addField = (e: React.MouseEvent<HTMLAnchorElement>) => {
        e.preventDefault();

        setFields([
            ...fields,
            new Field(),
        ]);
    };

    const deleteField = (e: React.MouseEvent<HTMLAnchorElement>) => {
        e.preventDefault();

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
        <div className="field-control mt-6">
            <div className="field-instruct">
                <label>Custom Fields</label>
            </div>
            <div className="">
                <div className="fields-keyvalue">
                    {fields.length < 1
                        && <div className="field-no-results">
                            <p>
                                No <b>custom fields</b> exist.
                                <a
                                    onClick={addField}
                                    href="#"
                                    rel="add_row"
                                >
                                    Add Field
                                </a>
                            </p>
                        </div>
                    }
                    {fields.length > 0
                        && <>
                            <div
                                className="fields-keyvalue-header"
                                style={{ display: 'flex' }}
                            >
                                <div className="field-instruct pl-2" style={{ width: '25%' }}>
                                    <label>Label</label>
                                </div>
                                <div className="field-instruct pl-2" style={{ width: '25%' }}>
                                    <label>Handle</label>
                                </div>
                                <div className="field-instruct pl-2" style={{ width: '25%' }}>
                                    <label>Type</label>
                                </div>
                                <div className="field-instruct pl-2" style={{ width: '25%' }}>
                                    <label>Required</label>
                                </div>
                            </div>
                            <SortableCont
                                onSortEnd={onSortEnd}
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
                            <a
                                onClick={addField}
                                href="#"
                                rel="add_row"
                                style={{ display: 'inline-block' }}
                                className="button button--default button--small"
                            >
                                Add A Field
                            </a>
                        </>
                    }
                </div>
            </div>
        </div>
    );
};

export default FieldSettingsFields;
