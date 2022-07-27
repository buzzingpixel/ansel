import * as React from 'react';
import { FaUpload } from 'react-icons/fa';
import { IconContext } from 'react-icons';

const DragInProgress = (
    { isDropZoneDragActive }: {isDropZoneDragActive: boolean},
) => {
    if (!isDropZoneDragActive) {
        return <></>;
    }

    return <div className="ansel_z-50 ansel_absolute ansel_w-full ansel_h-full ansel_bg-gray-50 ansel_opacity-95">
        <IconContext.Provider value={{ color: '#525252' }}>
            <FaUpload
                className="ansel_w-5 ansel_h-5 ansel_absolute ansel_top-1/2 ansel_left-1/2 ansel_transform"
                style={{ transform: 'translate(-50%, -50%)' }}
            />
        </IconContext.Provider>
    </div>;
};

export default DragInProgress;
