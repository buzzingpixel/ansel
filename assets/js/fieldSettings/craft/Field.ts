export default class Field {
    public label: string;

    public handle: string;

    public type: string;

    public require: boolean;

    constructor (
        {
            label,
            handle,
            type,
            require,
        }: {
            label?: string,
            handle?: string,
            type?: string,
            require?: boolean,
        },
    ) {
        this.label = label !== undefined ? label : '';

        this.handle = handle !== undefined ? handle : '';

        this.type = type !== undefined ? type : '';

        this.require = Boolean(require);
    }
}
