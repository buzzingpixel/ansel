import { useFieldSettings } from './FieldSettings/FieldSettingsContext';

interface ImageConstraintValidation {
    valid: boolean,
}

const useValidateImageConstraints = () => {
    const { minWidth, minHeight } = useFieldSettings();

    const validate = async (
        img: string,
    ) => new Promise<ImageConstraintValidation>((resolve, reject) => {
        const imgEl = new Image();

        const garbageCollect = () => {
            imgEl.onload = null;
            imgEl.onload = null;
            imgEl.src = '';
        };

        const timer = setTimeout(() => {
            garbageCollect();

            reject(new Error('errorLoadingImage'));
        }, 15000);

        imgEl.onload = () => {
            clearTimeout(timer);

            if (minWidth < 1 && minHeight < 1) {
                resolve({ valid: true });

                garbageCollect();

                return;
            }

            if (imgEl.width < minWidth || imgEl.height < minHeight) {
                resolve({ valid: false });

                garbageCollect();

                return;
            }

            resolve({ valid: true });

            garbageCollect();
        };

        imgEl.onerror = () => {
            reject(new Error('errorLoadingImage'));

            garbageCollect();
        };

        imgEl.src = img;
    });

    return { validate };
};

export default useValidateImageConstraints;
