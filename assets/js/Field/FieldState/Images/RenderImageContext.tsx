import {
    createContext, Dispatch, SetStateAction, useContext, useEffect, useMemo, useState,
} from 'react';
import * as React from 'react';
import { PercentCrop } from 'react-image-crop';
import ImageType from './ImageType';
import PixelCropPlusImageDimensionsContract from './PixelCropPlusImageDimensionsContract';
import { useFieldSettings } from '../../FieldSettings/FieldSettingsContext';
import GetPixelCropFromPercentCrop from './GetPixelCropFromPercentCrop';
import { useImages } from './ImagesContext';

interface FocalPoint {
    x: number,
    y: number,
}

interface RenderImageContextType {
    image: ImageType;
    cropIsOpen: boolean;
    setCropIsOpen: Dispatch<SetStateAction<boolean>>;
    toggleCropIsOpen: () => void;
    crop: PercentCrop;
    setCrop: Dispatch<SetStateAction<PercentCrop | null>>;
    acceptedCrop: PercentCrop;
    setAcceptedCrop: Dispatch<SetStateAction<PercentCrop | null>>;
    pixelCropState: PixelCropPlusImageDimensionsContract;
    setFocalPoint: (focalPoint: FocalPoint) => void;
    focalPointIsOpen: boolean;
    setFocalPointIsOpen: Dispatch<SetStateAction<boolean>>;
    toggleFocalPointIsOpen: () => void;
    setImage: (image: ImageType) => void;
    getFieldValue: (fieldHandle: string) => string | boolean;
    setFieldValue: (fieldHandle: string, value: string | boolean) => void;
}

const RenderImageContext = createContext<RenderImageContextType>(
    null,
);

const useRenderImageContext = () => {
    const context = useContext(RenderImageContext);

    if (!context) {
        throw new Error(
            'useRenderImageContext must be used within a RenderImageProvider',
        );
    }

    return context;
};

const RenderImageProvider = ({
    image,
    children,
}: {
    image: ImageType,
    children?:
        | React.ReactChild
        | React.ReactChild[],
}) => {
    const { images, setImages } = useImages();

    const { ratio, ratioAsNumber } = useFieldSettings();

    const [cropIsOpen, setCropIsOpen] = useState<boolean>(false);

    const [focalPointIsOpen, setFocalPointIsOpen] = useState<boolean>(
        false,
    );

    const [crop, setCrop] = useState<PercentCrop | null>(null);

    const [acceptedCrop, setAcceptedCropState] = useState<PercentCrop | null>(
        { ...crop },
    );

    const [
        pixelCropState,
        setPixelCropState,
    ] = useState<PixelCropPlusImageDimensionsContract|null>(null);

    const setAcceptedCrop = (cropToSet: PercentCrop) => {
        GetPixelCropFromPercentCrop(image.imageUrl, cropToSet).then(
            (incomingCrop) => {
                setAcceptedCropState(cropToSet);

                setPixelCropState({ ...incomingCrop });

                const index = images.findIndex(
                    (mappedImage) => mappedImage.id === image.id,
                );

                if (index === -1) {
                    return;
                }

                setImages((oldImagesState) => {
                    image.x = incomingCrop.x;
                    image.y = incomingCrop.y;
                    image.width = incomingCrop.width;
                    image.height = incomingCrop.height;

                    oldImagesState[index] = image;

                    return [...oldImagesState];
                });
            },
        );
    };

    const toggleCropIsOpen = () => {
        setCropIsOpen((prevState) => !prevState);
    };

    const setFocalPoint = (focalPoint: FocalPoint) => {
        setImages((oldImagesState) => {
            const index = images.findIndex(
                (mappedImage) => mappedImage.id === image.id,
            );

            image.focalX = focalPoint.x;
            image.focalY = focalPoint.y;

            oldImagesState[index] = image;

            return [...oldImagesState];
        });
    };

    useEffect(
        () => {
            if (crop === null) {
                if (!ratioAsNumber) {
                    const coords = {
                        unit: '%',
                        x: 0,
                        y: 0,
                        width: 100,
                        height: 100,
                    };

                    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
                    // @ts-ignore
                    setCrop(coords);

                    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
                    // @ts-ignore
                    setAcceptedCrop(coords);
                } else {
                    const imageElement = new Image();

                    imageElement.onload = () => {
                        const width = imageElement.naturalWidth;
                        const height = imageElement.naturalHeight;
                        const naturalRatio = width / height;
                        const ratioParts = ratio.split(':');
                        const ratioWidth = parseFloat(ratioParts[0]) as number;
                        const ratioHeight = parseFloat(ratioParts[1]) as number;

                        const coords = {
                            unit: '%',
                            x: 0,
                            y: 0,
                            width: 100,
                            height: 100,
                        };

                        if (ratioAsNumber < naturalRatio) {
                            const tmpRatio = ratioWidth / ratioHeight;
                            const newWidth = tmpRatio * height;
                            coords.width = (newWidth / width) * 100;

                            const leftOverPixels = width - newWidth;
                            const leftPixels = leftOverPixels / 2;
                            // left percent
                            coords.x = (leftPixels / width) * 100;
                        }

                        if (ratioAsNumber > naturalRatio) {
                            const tmpRatio = ratioHeight / ratioWidth;
                            const newHeight = tmpRatio * width;
                            coords.height = (newHeight / height) * 100;

                            const leftOverPixels = height - newHeight;
                            const topPixels = leftOverPixels / 2;
                            // top percent
                            coords.y = (topPixels / height) * 100;
                        }

                        // eslint-disable-next-line @typescript-eslint/ban-ts-comment
                        // @ts-ignore
                        setCrop(coords);

                        // eslint-disable-next-line @typescript-eslint/ban-ts-comment
                        // @ts-ignore
                        setAcceptedCrop(coords);
                    };

                    imageElement.src = image.imageUrl;
                }
            }

            if (!image.focalX || !image.focalY) {
                setFocalPoint({
                    x: 50,
                    y: 50,
                });
            }
        },
        [],
    );

    const toggleFocalPointIsOpen = () => {
        setFocalPointIsOpen((prevState) => !prevState);
    };

    const setImage = (imageToSet: ImageType) => {
        const index = images.findIndex(
            (mappedImage) => mappedImage.id === imageToSet.id,
        );

        if (index === -1) {
            return;
        }

        setImages((oldImagesState) => {
            oldImagesState[index] = imageToSet;

            return [...oldImagesState];
        });
    };

    const getFieldValue = (fieldHandle: string) => {
        const fieldData = image.fieldData || {};

        const thisField = fieldData[fieldHandle];

        return thisField?.value;
    };

    const setFieldValue = (fieldHandle: string, value: string | boolean) => {
        image.fieldData = image.fieldData || {};

        image.fieldData[fieldHandle] = {
            handle: fieldHandle,
            value,
        };

        setImage(image);
    };

    const value = useMemo(
        () => ({
            image,
            cropIsOpen,
            setCropIsOpen,
            toggleCropIsOpen,
            crop,
            setCrop,
            acceptedCrop,
            setAcceptedCrop,
            pixelCropState,
            setFocalPoint,
            focalPointIsOpen,
            setFocalPointIsOpen,
            toggleFocalPointIsOpen,
            setImage,
            getFieldValue,
            setFieldValue,
        }),
        [
            image,
            images,
            cropIsOpen,
            crop,
            acceptedCrop,
            pixelCropState,
            focalPointIsOpen,
        ],
    );

    return <RenderImageContext.Provider
        value={value}
        children={children}
    />;
};

export { RenderImageProvider, useRenderImageContext, FocalPoint };
