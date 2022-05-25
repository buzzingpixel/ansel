import * as React from 'react';
import FieldStateType from './Types/FieldStateType';
import FieldDataType from './Types/FieldDataType';

const FieldInputs = (
    {
        fieldData,
        fieldState,
    }: {
        fieldData: FieldDataType,
        fieldState: FieldStateType,
    },
) => {
    const inputBaseName = fieldData.inputPlaceholder.dataset.fieldNameRoot as string;

    return (
        <>
            <input
                type="hidden"
                name={`${inputBaseName}[images]`}
                value=""
            />
            {fieldState.images.map((image) => {
                const inputs = [
                    <input
                        type="hidden"
                        name={`${inputBaseName}[images][${image.uid}][uid]`}
                        value={image.uid}
                    />,
                ];

                if (image.imageUpload) {
                    inputs.push(
                        <input
                            type="hidden"
                            name={`${inputBaseName}[images][${image.uid}][cacheDirectory]`}
                            value={image.imageUpload.cacheDirectory}
                        />,
                    );

                    inputs.push(
                        <input
                            type="hidden"
                            name={`${inputBaseName}[images][${image.uid}][cacheFilePath]`}
                            value={image.imageUpload.cacheFilePath}
                        />,
                    );

                    inputs.push(
                        <input
                            type="hidden"
                            name={`${inputBaseName}[images][${image.uid}][fileName]`}
                            value={image.imageUpload.fileName}
                        />,
                    );
                }

                return (<>{inputs}</>);
            })}
        </>
    );
};

export default FieldInputs;