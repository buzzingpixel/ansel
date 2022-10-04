import ImageType from '../FieldState/Images/ImageType';

interface PostedData {
    images: Array<ImageType>,
    deletions: Array<string>,
}

const DataFromRootEl = (anselFieldEl: HTMLDivElement): PostedData => {
    const dataEl = anselFieldEl.getElementsByClassName(
        'ansel_data',
    ).item(0) as HTMLSpanElement;

    const { json } = dataEl.dataset;

    return JSON.parse(json) as PostedData;
};

export default DataFromRootEl;
