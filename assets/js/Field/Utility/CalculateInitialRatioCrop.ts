import ImageType from '../Types/ImageType';
import FieldSettingsType from '../Types/FieldSettingsType';

const CalculateInitialRatioCrop = (
    {
        image,
        setCrop,
        fieldSettings,
        setAcceptedCrop,
    }: {
        image: ImageType,
        setCrop: CallableFunction,
        fieldSettings: FieldSettingsType,
        setAcceptedCrop: CallableFunction,
    },
) => {
    const imageElement = new Image();

    imageElement.onload = () => {
        const width = imageElement.naturalWidth;
        const height = imageElement.naturalHeight;
        const naturalRatio = width / height;
        const ratioParts = fieldSettings.ratio.split(':');
        const ratioWidth = parseFloat(ratioParts[0]) as number;
        const ratioHeight = parseFloat(ratioParts[1]) as number;

        const coords = {
            unit: '%',
            x: 0,
            y: 0,
            width: 100,
            height: 100,
        };

        if (fieldSettings.ratioAsNumber < naturalRatio) {
            const tmpRatio = ratioWidth / ratioHeight;
            const newWidth = tmpRatio * height;
            coords.width = (newWidth / width) * 100;

            const leftOverPixels = width - newWidth;
            const leftPixels = leftOverPixels / 2;
            // left percent
            coords.x = (leftPixels / width) * 100;
        }

        if (fieldSettings.ratioAsNumber > naturalRatio) {
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
};

export default CalculateInitialRatioCrop;
