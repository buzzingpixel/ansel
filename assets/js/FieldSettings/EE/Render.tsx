import * as React from 'react';
import * as ReactDOM from 'react-dom';
import FieldSettingsFields from './FieldSettingsFields';

export default (fieldSettingsFieldsContainer: Element) => {
    const templateInput: HTMLInputElement = fieldSettingsFieldsContainer
        .getElementsByClassName(
            'customFields',
        ).item(0) as HTMLInputElement;

    ReactDOM.render(
        <FieldSettingsFields
            templateInput={templateInput}
            existingFields={JSON.parse(templateInput.dataset.existingFields)}
        />,
        fieldSettingsFieldsContainer,
    );
};
