import * as React from 'react';
import FieldSettingsType from './FieldSettingsType';
import CustomFieldType from './CustomFieldType';

const Field = (
    {
        fieldSettings,
        customFields,
    }: {
        fieldSettings: FieldSettingsType,
        customFields: Array<CustomFieldType>,
    },
) => {
    console.log(fieldSettings, customFields);

    return (
        <>foo field</>
    );
};

export default Field;
