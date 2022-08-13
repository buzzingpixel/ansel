import { useEffect, useState } from 'react';

interface WindowDimensions {
    width: number;
    height: number;
}

const useWindowResize = (onResize?: () => void) => {
    const hasWindow = typeof window !== 'undefined';

    const getWindowDimensions = () => {
        const width = hasWindow ? window.innerWidth : null;

        const height = hasWindow ? window.innerHeight : null;

        return {
            width,
            height,
        };
    };

    const [windowDimensions, setWindowDimensions] = useState<WindowDimensions>(
        getWindowDimensions(),
    );

    useEffect(() => {
        if (!hasWindow) {
            return () => null;
        }

        const handleResize = () => {
            if (onResize) {
                onResize();
            }

            setWindowDimensions(getWindowDimensions());
        };

        window.addEventListener('resize', handleResize);

        return () => window.removeEventListener('resize', handleResize);
    }, [hasWindow]);

    return windowDimensions;
};

export default useWindowResize;
