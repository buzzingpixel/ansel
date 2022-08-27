import * as React from 'react';
import { useRef, useState } from 'react';
import { v4 as uuid } from 'uuid';
import { useRenderImagesFieldsContext } from './RenderImagesFieldsContext';
import { useCustomFields } from '../../CustomFields/CustomFieldsContext';
import AnselPortal from '../../Utility/AnselPortal';
import { useRenderImageContext } from './RenderImageContext';
import useWindowResize from '../../../Hooks/useWindowResize';
import CustomFieldType from '../../CustomFields/CustomFieldType';
import LightSwitch from '../../../Shared/LightSwitch';

function RenderBooleanFieldType () {
    // TODO: Set default state and set value
    return <div><LightSwitch defaultState={true} /></div>;
}

function RenderTextFieldType () {
    // TODO: Set default state and set value
    return <div>
        <input
            type="text"
            className="ansel_border-solid ansel_box-border ansel_block ansel_w-full sm:ansel_text-sm ansel_border-gray-300 ansel_rounded-md ansel_py-8px ansel_px-15px ansel_outline-0"
        />
    </div>;
}

const RenderField = ({ field }: { field: CustomFieldType }) => {
    if (field.type === 'bool') {
        return <RenderBooleanFieldType />;
    }

    return <RenderTextFieldType />;
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
    const customFields = useCustomFields();

    const prev = () => {
        // eslint-disable-next-line no-console
        console.log('prev');
    };

    const cancel = () => {
        // eslint-disable-next-line no-console
        console.log('cancel');
    };

    const accept = () => {
        // eslint-disable-next-line no-console
        console.log('accept');
    };

    const next = () => {
        // eslint-disable-next-line no-console
        console.log('next');
    };

    return (
        <AnselPortal
            prevAction={prev}
            cancelAction={cancel}
            acceptAction={accept}
            nextAction={next}
        >
            <div className="ansel_w-screen-40px ansel_max-w-xl ansel_m-4">
                <div className="ansel_bg-gray-50 ansel_w-full ansel_p-4 ansel_rounded-lg ansel_max-height-inner-portal ansel_overflow-y-auto ansel_webkit-overflow-scrolling-touch">
                    <div className="ansel_pb-4">
                        <Thumbnail />
                    </div>
                    <div className="ansel_relative">
                        <div className="ansel_my-4 ansel_text-left">
                            {customFields.map((field) => {
                                const fieldUuid = uuid();

                                return (
                                    <div
                                        key={fieldUuid}
                                        className="ansel_my-4"
                                    >
                                        <label
                                            htmlFor={fieldUuid}
                                            className="ansel_font-bold ansel_mb-5px ansel_block"
                                        >
                                            {field.label}
                                            {field.required && <span className="ansel_field-required"></span>}
                                        </label>
                                        <RenderField
                                            field={field}
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
    const { activeFieldImageId } = useRenderImagesFieldsContext();

    if (!activeFieldImageId) {
        return <></>;
    }

    return <RenderImageFieldsInner />;
};

export default RenderImageFields;
