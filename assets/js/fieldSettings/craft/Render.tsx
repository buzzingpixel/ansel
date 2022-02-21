import ReactDOM = require('react-dom');
import FieldSettingsFields from './FieldSettingsFields';

export default (fieldSettingsFieldsContainer: Element) => {
    const templateInput: HTMLInputElement = fieldSettingsFieldsContainer
        .getElementsByClassName(
            'custom_fields',
        ).item(0) as HTMLInputElement;

    ReactDOM.render(
        <FieldSettingsFields templateInput={templateInput} />,
        fieldSettingsFieldsContainer,
    );
};
