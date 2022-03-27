// eslint-disable-next-line no-shadow
export const enum UploadJsonMessageReturnType {
    error = 'error',
    success = 'success',
}

interface UploadJsonMessageReturn {
    type: UploadJsonMessageReturnType;
    message: string;
}

export default UploadJsonMessageReturn;
