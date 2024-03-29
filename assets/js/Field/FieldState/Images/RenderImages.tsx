import * as React from 'react';
import { ReactSortable } from 'react-sortablejs';
import { ForwardedRef, forwardRef } from 'react';
import { useImages } from './ImagesContext';
import RenderImage from './RenderImage';
import { RenderImageProvider } from './RenderImageContext';
import { RenderImagesFieldsProvider } from './RenderImagesFieldsContext';

const UlComponent = forwardRef((
    props: {
        children?:
            | React.ReactChild
            | React.ReactChild[],
    },
    ref: ForwardedRef<HTMLUListElement>,
) => <ul
    role="list"
    className="ansel_grid ansel_grid-cols-1 ansel_gap-6 md:ansel_grid-cols-2 2xl:ansel_grid-cols-3 3xl:ansel_grid-cols-4"
    ref={ref}
>
    {props.children}
</ul>);

const RenderImages = () => {
    const { images, setImages } = useImages();

    return <ReactSortable
        tag={UlComponent}
        list={images}
        setList={setImages}
        animation={200}
        handle='.ansel-drag-handle'
    >
        <RenderImagesFieldsProvider>
            {images.map((image) => <RenderImageProvider
                key={`image-provider-${image.id}`}
                image={image}
            >
                <RenderImage />
            </RenderImageProvider>)}
        </RenderImagesFieldsProvider>
    </ReactSortable>;
};

export default RenderImages;
