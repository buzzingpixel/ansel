/**
 * Create the interface to represent EE Grid
 */

// ESLint says it's already defined when it's clearly not
// eslint-disable-next-line no-shadow
export enum GridBindEvent {
    // Called when a row is displayed on the publish form
    display = 'display',
    // Called when a row is deleted from the publish form
    remove = 'remove',
    // Called before a row starts sorting on the publish form
    beforeSort = 'beforeSort',
    // Called after a row finishes sorting on the publish form
    afterSort = 'afterSort',
    // Called when a fieldtype’s settings form is displayed on the Grid field settings page
    displaySettings = 'displaySettings',
}

type GridBindCallback = (cell: JQuery) => void;

interface Grid {
    bind: (
        fieldName: string,
        event: GridBindEvent,
        callback:GridBindCallback,
    ) => void;
}

export default Grid;
