import FieldSettingsType from '../Field/Types/FieldSettingsType';

interface ImageConstraintValidation {
    valid: boolean,
}

const ValidateImageConstraints = async (
    img: string,
    fieldSettings: FieldSettingsType,
) => new Promise<ImageConstraintValidation>((resolve, reject) => {
    let imgEl = new Image();

    const garbageCollect = () => {
        imgEl.onload = null;

        imgEl.onerror = null;

        imgEl.src = '//!!!!/test.jpg';

        imgEl = null;
    };

    const timer = setTimeout(() => {
        garbageCollect();

        // TODO: Get this from translations
        reject(new Error('There was an error loading the image.'));
    }, 15000);

    imgEl.onload = () => {
        clearTimeout(timer);

        if (fieldSettings.minWidth < 1 && fieldSettings.minHeight < 1) {
            resolve({ valid: true });

            garbageCollect();

            return;
        }

        if (
            imgEl.width < fieldSettings.minWidth
            || imgEl.height < fieldSettings.minHeight
        ) {
            resolve({ valid: false });

            garbageCollect();

            return;
        }

        resolve({ valid: true });

        garbageCollect();
    };

    imgEl.onerror = () => {
        // TODO: Get this from translations
        reject(new Error('There was an error loading the image.'));

        garbageCollect();
    };

    imgEl.src = img;
});

export default ValidateImageConstraints;
