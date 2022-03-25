import FieldParametersType from '../../FieldParametersType';

const FileHandler = (
    file: File,
    setImages: CallableFunction,
    parameters: FieldParametersType,
) => {
    const formData = new FormData();

    formData.append('image', file);

    console.log(parameters);

    fetch(parameters.uploadUrl, {
        method: 'POST',
        body: formData,
    }).then((res) => {
        console.log(res.body);
    }).catch((error) => {
        console.log(error);
    });
};

export default FileHandler;
