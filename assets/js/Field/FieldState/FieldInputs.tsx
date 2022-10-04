import * as React from 'react';
import { useEffect, useState } from 'react';
import { useInputPlaceholder } from '../InputPlaceholder/InputPlaceholderContext';
import { useImages } from './Images/ImagesContext';
import { useRootEl } from '../RootElProvider';
import { usePlatform } from '../Platform/PlatformContext';
import { Environment } from '../Platform/PlatformType';

const FieldInputs = () => {
    const { images, deletedIds } = useImages();

    const inputPlaceholder = useInputPlaceholder();

    const inputBaseName = inputPlaceholder.name;

    const rootEl = useRootEl();

    const platform = usePlatform();

    const [shouldRunValidation, setShouldRunValidation] = useState(
        false,
    );

    // This is so gross
    if (platform.environment === Environment.ee) {
        useEffect(() => {
            const $errorMessages = $(rootEl.parentNode).find(
                '.ee-form-error-message',
            );

            if ($errorMessages.length > 0 && !shouldRunValidation) {
                setShouldRunValidation(true);
            }

            if (shouldRunValidation) {
                // eslint-disable-next-line @typescript-eslint/ban-ts-comment
                // @ts-ignore
                // eslint-disable-next-line no-underscore-dangle
                EE.cp.formValidation._sendAjaxRequest(
                    $(rootEl.parentNode).find(
                        'input[type=hidden]',
                    ),
                );
            }
        });
    }

    return <>
        <input
            type="hidden"
            name={`${inputBaseName}[placeholder]`}
            value="placeholder"
        />
        {images.map((image) => {
            const localImage = { ...image };

            return <input
                key={`image-input-${image.id}`}
                type="hidden"
                name={`${inputBaseName}[images][${image.id}]`}
                value={JSON.stringify(localImage)}
            />;
        })}
        {deletedIds.length > 0 && <input
            type="hidden"
            name={`${inputBaseName}[delete_images]`}
            value={JSON.stringify(deletedIds)}
        />}
    </>;
};

export default FieldInputs;
