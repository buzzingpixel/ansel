import ReactDOM = require('react-dom');
import FieldSettingsFields from './FieldSettingsFields';

export default (fieldSettingsFieldsContainer: Element) => {
    const templateInput: Element = fieldSettingsFieldsContainer.getElementsByClassName(
        'custom_fields',
    ).item(0);

    ReactDOM.render(
        <FieldSettingsFields
            templateInput={templateInput}
        />,
        fieldSettingsFieldsContainer,
    );
};
