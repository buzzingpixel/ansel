import * as React from 'react';
import RenderThumbnail from './RenderThumbnail';
import DeleteButton from './DeleteButton';
import EditFieldsButton from './EditFieldsButton';
import EditImageButton from './EditImageButton';
import EditFocalPointButton from './EditFocalPointButton';
import RenderImageCrop from './RenderImageCrop';

//  ansel-field-working
const RenderImage = () => <li className="ansel_col-span-1 ansel_flex ansel_flex-col ansel_text-center ansel_bg-white ansel_rounded-lg ansel_shadow ansel_divide-y ansel_divide-gray-200">
    <RenderImageCrop />
    <div className="ansel-drag-handle ansel_flex-1 ansel_flex ansel_flex-col ansel_p-8 ansel_cursor-grab">
        <RenderThumbnail />
    </div>
    <div>
        <div
            className="ansel_-mt-px ansel_flex ansel_border-0 ansel_border-t ansel_border-gray-200 ansel_border-solid">
            <EditFieldsButton/>
            <EditImageButton/>
            <EditFocalPointButton/>
            <DeleteButton />
        </div>
    </div>
</li>;

export default RenderImage;
