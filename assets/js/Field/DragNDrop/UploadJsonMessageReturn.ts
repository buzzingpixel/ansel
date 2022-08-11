// eslint-disable-next-line no-shadow
export const enum UploadJsonMessageReturnType {
    error = 'error',
    success = 'success',
}

interface UploadJsonMessageReturn {
    type: UploadJsonMessageReturnType;
    message: string;
    fileName?: string;
    cacheDirectory?: string;
    cacheFilePath?: string;
    base64Image?: string;
}

export default UploadJsonMessageReturn;
