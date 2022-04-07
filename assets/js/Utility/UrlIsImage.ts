const UrlIsImage = async (url: string) => new Promise<void>((resolve, reject) => {
    fetch(url)
        .then((response) => ({
            contentType: response.headers.get('Content-Type'),
            raw: response.blob(),
        }))
        .then((data) => {
            if (data.contentType === 'image/svg+xml') {
                reject();
            }

            const img = new Image();

            // eslint-disable-next-line no-multi-assign
            img.onerror = img.onabort = () => {
                reject();
            };

            img.onload = () => {
                resolve();
            };

            img.src = url;

            setTimeout(() => {
                // set src to an invalid URL so it stops trying to load
                img.src = '//!!!!/test.jpg';
            }, 10000);
        })
        .catch(() => {
            reject();
        });
});

export default UrlIsImage;
