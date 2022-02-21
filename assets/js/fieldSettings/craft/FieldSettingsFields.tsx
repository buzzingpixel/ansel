import * as React from 'react';
import Field from './Field';

type Props = Record<string, unknown>;

type FieldSettingsFieldsState = {
    fields: Array<Field>,
};

export default class FieldSettingsFields extends React.Component<
    Props,
    FieldSettingsFieldsState
> {
    state: FieldSettingsFieldsState;

    private readonly inputNameBase: string;

    constructor ({ templateInput }: {templateInput: HTMLInputElement}) {
        super({});

        this.inputNameBase = templateInput.name;

        this.state = { fields: [] };

        this.addField = this.addField.bind(this);

        this.deleteField = this.deleteField.bind(this);

        this.updateLabel = this.updateLabel.bind(this);

        this.updateHandle = this.updateHandle.bind(this);

        this.updateType = this.updateType.bind(this);

        this.updateRequire = this.updateRequire.bind(this);
    }

    addField () {
        const { fields } = this.state;

        fields.push(new Field({ require: true }));

        this.setState({ fields });
    }

    deleteField (e: React.MouseEvent<HTMLAnchorElement>) {
        const { fields } = this.state;

        fields.splice(
            parseInt(e.currentTarget.dataset.index, 10),
            1,
        );

        this.setState({ fields });
    }

    updateLabel (e: React.ChangeEvent<HTMLTextAreaElement>) {
        const index = parseInt(e.currentTarget.dataset.index, 10);

        const { fields } = this.state;

        fields[index].label = String(e.currentTarget.value);

        this.setState({ fields });
    }

    updateHandle (e: React.ChangeEvent<HTMLTextAreaElement>) {
        const index = parseInt(e.currentTarget.dataset.index, 10);

        const { fields } = this.state;

        fields[index].handle = String(e.currentTarget.value);

        this.setState({ fields });
    }

    updateType (e: React.ChangeEvent<HTMLSelectElement>) {
        const index = parseInt(e.currentTarget.dataset.index, 10);

        const { fields } = this.state;

        fields[index].type = String(e.currentTarget.value);

        this.setState({ fields });
    }

    updateRequire (e: React.ChangeEvent<HTMLInputElement>) {
        const index = parseInt(e.currentTarget.dataset.index, 10);

        const { fields } = this.state;

        fields[index].require = !fields[index].require;

        this.setState({ fields });
    }

    render () {
        return (
            <div className="field">
                <div className="heading"><label>Custom Fields</label></div>
                <div className="input ltr">
                    <input
                        type="hidden"
                        name={`${this.inputNameBase}[]`}
                        value=""
                    />
                    <table className="editable fullwidth">
                        <thead>
                            <tr>
                                <th scope="col" className="singleline-cell textual">Label</th>
                                <th scope="col" className="singleline-cell textual">Handle</th>
                                <th scope="col" className="singleline-cell textual">Type</th>
                                <th scope="col" className="singleline-cell textual">Require</th>
                                <th colSpan={2} />
                            </tr>
                        </thead>
                        <tbody>
                            {this.state.fields.map((field, index) => (
                                    <tr>
                                        <td className="singleline-cell textual">
                                            <textarea
                                                onChange={this.updateLabel}
                                                name={`${this.inputNameBase}[${index}][label]`}
                                                rows={1}
                                                style={{ minHeight: '34px', boxShadow: 'none' }}
                                                value={field.label}
                                                data-index={index}
                                                className='focus:outline-none'
                                            />
                                        </td>
                                        <td className="code singleline-cell textual">
                                            <textarea
                                                onChange={this.updateHandle}
                                                name={`${this.inputNameBase}[${index}][handle]`}
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
                                                        onChange={this.updateType}
                                                        name={`${this.inputNameBase}[${index}][type]`}
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
                                                    name={`${this.inputNameBase}[${index}][required]`}
                                                    value=""
                                                />
                                                <input
                                                    onChange={this.updateRequire}
                                                    id={`checkbox${index}`}
                                                    type="checkbox"
                                                    className="checkbox"
                                                    name={`${this.inputNameBase}[${index}][required]`}
                                                    value="1"
                                                    checked={field.require}
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
                                                onClick={this.deleteField}
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
                        onClick={this.addField}
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
    }
}
