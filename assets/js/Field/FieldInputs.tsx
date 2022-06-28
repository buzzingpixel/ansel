import * as React from 'react';
import { v4 as uuid } from 'uuid';
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
            <input
                type="hidden"
                name={`${inputBaseName}[delete]`}
                value=""
            />
            {fieldState.delete.map((uid) => <input
                key={uuid()}
                type="hidden"
                name={`${inputBaseName}[delete][${uid}]`}
                value={uid}
            />)}
            {fieldState.images.map((image) => {
                const inputs = [
                    <input
                        key={uuid()}
                        type="hidden"
                        name={`${inputBaseName}[images][${image.uid}][uid]`}
                        value={image.uid}
                    />,
                    <input
                        key={uuid()}
                        type="hidden"
                        name={`${inputBaseName}[images][${image.uid}][sourceImageId]`}
                        value={image.sourceImageId}
                    />,
                    <input
                        key={uuid()}
                        type="hidden"
                        name={`${inputBaseName}[images][${image.uid}][x]`}
                        value={image.x}
                    />,
                    <input
                        key={uuid()}
                        type="hidden"
                        name={`${inputBaseName}[images][${image.uid}][y]`}
                        value={image.y}
                    />,
                    <input
                        key={uuid()}
                        type="hidden"
                        name={`${inputBaseName}[images][${image.uid}][width]`}
                        value={image.width}
                    />,
                    <input
                        key={uuid()}
                        type="hidden"
                        name={`${inputBaseName}[images][${image.uid}][height]`}
                        value={image.height}
                    />,
                ];

                if (image.imageUpload) {
                    inputs.push(
                        <input
                            key={uuid()}
                            type="hidden"
                            name={`${inputBaseName}[images][${image.uid}][cacheDirectory]`}
                            value={image.imageUpload.cacheDirectory}
                        />,
                    );

                    inputs.push(
                        <input
                            key={uuid()}
                            type="hidden"
                            name={`${inputBaseName}[images][${image.uid}][cacheFilePath]`}
                            value={image.imageUpload.cacheFilePath}
                        />,
                    );

                    inputs.push(
                        <input
                            key={uuid()}
                            type="hidden"
                            name={`${inputBaseName}[images][${image.uid}][fileName]`}
                            value={image.imageUpload.fileName}
                        />,
                    );
                }

                return (<div className="ansel_hidden" key={uuid()}>{inputs}</div>);
            })}
        </>
    );
};

export default FieldInputs;
