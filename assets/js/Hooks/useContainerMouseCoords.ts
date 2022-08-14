import { useEffect, useState } from 'react';

export interface CoordsContract {
    x: number;
    y: number;
}

const useContainerMouseCoords = (initialCoords: CoordsContract) => {
    const [coords, setCoords] = useState<CoordsContract>(initialCoords);

    const [globalCoords, setGlobalCoords] = useState<CoordsContract>(
        initialCoords,
    );

    useEffect(() => {
        const handleWindowMouseMove = (event) => {
            let x = event.clientX;
            if (x > window.innerWidth) {
                x = window.innerWidth;
            } else if (x < 0) {
                x = 0;
            }

            let y = event.clientY;
            if (y > window.innerHeight) {
                y = window.innerHeight;
            } else if (y < 0) {
                y = 0;
            }

            let xPercent = (x / window.innerWidth) * 100;
            if (xPercent > 100) {
                xPercent = 100;
            } else if (xPercent < 0) {
                xPercent = 0;
            }

            let yPercent = (y / window.innerHeight) * 100;
            if (yPercent > 100) {
                yPercent = 100;
            } else if (xPercent < 0) {
                yPercent = 0;
            }

            setGlobalCoords({
                x: xPercent,
                y: yPercent,
            });
        };

        window.addEventListener('mousemove', handleWindowMouseMove);

        return () => window.removeEventListener(
            'mousemove',
            handleWindowMouseMove,
        );
    }, []);

    const handleMouseMove = (event) => {
        const boundingRect = event.currentTarget.getBoundingClientRect();

        let x = event.clientX - boundingRect.left;
        if (x > boundingRect.width) {
            x = boundingRect.width;
        } else if (x < 0) {
            x = 0;
        }

        let y = event.clientY - boundingRect.top;
        if (y > boundingRect.height) {
            y = boundingRect.height;
        } else if (y < 0) {
            y = 0;
        }

        let xPercent = (x / boundingRect.width) * 100;
        if (xPercent > 100) {
            xPercent = 100;
        } else if (xPercent < 0) {
            xPercent = 0;
        }

        let yPercent = (y / boundingRect.height) * 100;
        if (yPercent > 100) {
            yPercent = 100;
        } else if (xPercent < 0) {
            yPercent = 0;
        }

        setCoords({
            x: xPercent,
            y: yPercent,
        });
    };

    return { coords, globalCoords, handleMouseMove };
};

export default useContainerMouseCoords;
