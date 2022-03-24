import FileHandler from './FileHandler';

const OnDropAccepted = (
    files: Array<File>,
    setImages: CallableFunction,
) => {
    files.forEach((file) => {
        FileHandler(file, setImages);
    });
};

export default OnDropAccepted;
