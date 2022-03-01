interface FieldSettingsType {
    uploadLocation: string,
    saveLocation: string,
    minQty: number,
    maxQty: number,
    preventUploadOverMax: boolean,
    quality: number,
    forceJpg: boolean,
    retinaMode: boolean,
    minWidth: number,
    minHeight: number,
    maxWidth: number,
    maxHeight: number,
    ratio: string,
}

export default FieldSettingsType;
