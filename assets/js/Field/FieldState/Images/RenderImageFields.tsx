import * as React from 'react';
import { useRef, useState } from 'react';
import { useRenderImagesFieldsContext } from './RenderImagesFieldsContext';
import { useCustomFields } from '../../CustomFields/CustomFieldsContext';
import AnselPortal from '../../Utility/AnselPortal';
import { useRenderImageContext } from './RenderImageContext';
import useWindowResize from '../../../Hooks/useWindowResize';
import CustomFieldType from '../../CustomFields/CustomFieldType';
import LightSwitch from '../../../Shared/LightSwitch';
import { useImages } from './ImagesContext';

function RenderBooleanFieldType ({
    checked,
    setFieldValue,
}: {
    checked: boolean,
    setFieldValue: (val: string|boolean) => void,
}) {
    const changeHandler = (isChecked) => {
        setFieldValue(isChecked);
    };

    return <div><LightSwitch checked={checked} onChange={changeHandler} /></div>;
}

function RenderTextFieldType ({
    value,
    setFieldValue,
}: {
    value: string,
    setFieldValue: (val: string|boolean) => void,
}) {
    const [state, setState] = useState<string>(value);

    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setState(event.target.value);
        setFieldValue(event.target.value);
    };

    return <div>
        <input
            type="text"
            className="ansel_border-solid ansel_box-border ansel_block ansel_w-full sm:ansel_text-sm ansel_border-gray-300 ansel_rounded-md ansel_py-8px ansel_px-15px ansel_outline-0"
            value={state}
            onChange={handleChange}
        />
    </div>;
}

const RenderField = ({
    field,
    setFieldValue,
}: {
    field: CustomFieldType,
    setFieldValue: (val: string|boolean) => void,
}) => {
    const { getFieldValue } = useRenderImageContext();

    const value = getFieldValue(field.handle);

    if (field.type === 'bool') {
        return <RenderBooleanFieldType
            checked={value === true}
            setFieldValue={setFieldValue}
        />;
    }

    return <RenderTextFieldType
        value={String(value || '').toString()}
        setFieldValue={setFieldValue}
    />;
};

const Thumbnail = () => {
    const { image } = useRenderImageContext();

    const containerRef = useRef<HTMLDivElement|null>();

    const { pixelCropState } = useRenderImageContext();

    const [containerWidth, setContainerWidth] = useState<number>(
        192,
    );

    useWindowResize(() => {
        if (!containerRef || !containerRef.current) {
            return;
        }

        setContainerWidth(containerRef.current.clientWidth);
    });

    if (pixelCropState === null) {
        return (
            <div className="ansel_w-48 ansel_flex-shrink-0 ansel_mx-auto ansel_overflow-hidden ansel_relative ansel_bg-gray-400"
                 style={{
                     height: '90px',
                     position: 'relative',
                 }}
            />
        );
    }

    const ratio = pixelCropState.width / pixelCropState.height;

    const containerHeight = Math.round(containerWidth / ratio);

    const widthPercent = containerWidth / pixelCropState.width;
    const heightPercent = containerHeight / pixelCropState.height;
    const thumbWidth = Math.round(widthPercent * pixelCropState.imageWidth);
    const thumbHeight = Math.round(heightPercent * pixelCropState.imageHeight);
    const thumbX = Math.round(widthPercent * pixelCropState.x) * -1;
    const thumbY = Math.round(heightPercent * pixelCropState.y) * -1;

    return (
        <div
            ref={containerRef}
            className="ansel_w-48 ansel_flex-shrink-0 ansel_mx-auto ansel_overflow-hidden ansel_relative"
            style={{
                height: `${containerHeight}px`,
                position: 'relative',
            }}
        >
            <img
                src={image.imageUrl}
                style={{
                    position: 'absolute',
                    width: `${thumbWidth}px`,
                    height: `${thumbHeight}px`,
                    left: `${thumbX}px`,
                    top: `${thumbY}px`,
                    maxWidth: 'none',
                }}
                alt=""
            />
        </div>
    );
};

const RenderImageFieldsInner = () => {
    const { images } = useImages();

    const { setActiveFieldImageId } = useRenderImagesFieldsContext();

    const { getFieldValue, setFieldValue } = useRenderImageContext();

    const { image } = useRenderImageContext();

    const customFields = useCustomFields();

    const [localFieldValues, setLocalFieldValues] = useState(() => {
        const val = {};
        customFields.forEach((customField) => {
            const { handle } = customField;
            val[handle] = getFieldValue(handle);

            if (val[handle] === undefined) {
                switch (customField.type) {
                    case 'bool':
                        val[handle] = false;
                        break;
                    default:
                        val[handle] = '';
                }
            }
        });
        return val;
    });

    const acceptAllFieldValues = () => {
        Object.keys(localFieldValues).forEach((key) => {
            setFieldValue(key, localFieldValues[key]);
        });
    };

    const currentIndex = images.findIndex((item) => item.id === image.id);

    const prevIndex = currentIndex - 1;

    const prevImage = prevIndex > -1 ? images[prevIndex] : null;

    const prevImageId = prevImage?.id || null;

    const prev = () => {
        acceptAllFieldValues();

        if (!prevImageId) {
            return;
        }

        setActiveFieldImageId(String(prevImageId).toString());
    };

    const cancel = () => {
        setActiveFieldImageId(null);
    };

    const accept = () => {
        acceptAllFieldValues();
        setActiveFieldImageId(null);
    };

    const nextIndex = currentIndex + 1;

    const nextImage = images[nextIndex] || null;

    const nextImageId = nextImage?.id || null;

    const next = () => {
        acceptAllFieldValues();

        if (!nextImageId) {
            return;
        }

        setActiveFieldImageId(String(nextImageId).toString());
    };

    return (
        <AnselPortal
            prevAction={prev}
            prevActionDisabled={!prevImageId}
            cancelAction={cancel}
            acceptAction={accept}
            nextAction={next}
            nextActionDisabled={!nextImageId}
        >
            <div className="ansel_w-screen-40px ansel_max-w-xl ansel_m-4">
                <div className="ansel_bg-gray-50 ansel_w-full ansel_p-4 ansel_rounded-lg ansel_max-height-inner-portal ansel_overflow-y-auto ansel_webkit-overflow-scrolling-touch">
                    <div className="ansel_pb-4">
                        <Thumbnail />
                    </div>
                    <div className="ansel_relative">
                        <div className="ansel_my-4 ansel_text-left">
                            {customFields.map((field) => {
                                const fieldKey = `${image.id}-${field.handle}`;

                                const setFieldValueLocal = (val: string|boolean) => {
                                    setLocalFieldValues((oldVal) => {
                                        oldVal[field.handle] = val;
                                        return { ...oldVal };
                                    });
                                };

                                return (
                                    <div
                                        key={fieldKey}
                                        className="ansel_my-4"
                                    >
                                        <label
                                            htmlFor={fieldKey}
                                            className="ansel_font-bold ansel_mb-5px ansel_block"
                                        >
                                            {field.label}
                                            {field.required && <span className="ansel_field-required"></span>}
                                        </label>
                                        <RenderField
                                            field={field}
                                            setFieldValue={setFieldValueLocal}
                                        />
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </div>
            </div>
        </AnselPortal>
    );
};

const RenderImageFields = () => {
    const { image } = useRenderImageContext();

    const { activeFieldImageId } = useRenderImagesFieldsContext();

    if (activeFieldImageId !== image.id) {
        return <></>;
    }

    return <RenderImageFieldsInner />;
};

export default RenderImageFields;
