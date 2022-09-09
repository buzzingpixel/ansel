// eslint-disable-next-line no-shadow
export enum Environment {
    ee = 'ee',
    craft = 'craft',
}

interface PlatformType {
    environment: Environment;
    fileChooserModalLink?: string;
    uploadLocationFolderId?: string;
}

export default PlatformType;
