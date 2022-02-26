type FieldConstructor = {
    label?: string,
    handle?: string,
    type?: string,
    required?: boolean,
};

export default class Field {
    public label: string;

    public handle: string;

    public type: string;

    public required: boolean;

    constructor (
        {
            label,
            handle,
            type,
            required,
        }: FieldConstructor = {},
    ) {
        this.label = label !== undefined ? label : '';

        this.handle = handle !== undefined ? handle : '';

        this.type = type !== undefined ? type : '';

        this.required = Boolean(required);
    }
}
