import crypto from 'crypto';
import fs from 'fs-extra';
import hexRGBA from 'postcss-hexrgba';
import out from 'cli-output';
import path from 'path';
import postcss from 'postcss';
import postcssPresetEnv from 'postcss-preset-env';
import recursive from 'recursive-readdir-sync';
import tailwindcss from 'tailwindcss';
import CleanCss from 'clean-css';

/**
 * @see https://github.com/postcss/postcss/issues/1444#issuecomment-830675097
 */
const clean = (opts = {}) => {
    const cleanCss = new CleanCss(opts);

    return {
        postcssPlugin: 'clean',
        Once (css, { result }) {
            return new Promise((resolve, reject) => {
                // eslint-disable-next-line consistent-return
                cleanCss.minify(css.toString(), (err, min) => {
                    if (err) {
                        return reject(new Error(err.join('\n')));
                    }

                    // eslint-disable-next-line no-restricted-syntax
                    for (const w of min.warnings) {
                        result.warn(w);
                    }

                    result.root = postcss.parse(min.styles);
                    resolve();
                });
            });
        },
    };
};

const appDir = process.cwd();
const cssLocation = `${appDir}/assets/css`;
const cssOutputPath = `${appDir}/assetsDist/css`;
const cssOutputFileName = 'ansel.min';
const startFile = `${appDir}/assets/css/start.pcss`;
const libDir = `${appDir}/assets/css/lib`;
const include = [
    `${appDir}/node_modules/react-image-crop/dist/ReactCrop.css`,
];

export default () => {
    const mediaQueries = [];
    const mediaQueryCss = {};
    const files = {};
    let cssString = include.map((filePath) => fs.readFileSync(
        filePath,
    ).toString()).join('\n');

    // Let the user know what we're doing
    out.info('Compiling CSS...');

    // Add the reset file
    mediaQueries.push(0);
    mediaQueryCss[0] = String(fs.readFileSync(startFile));
    files[startFile] = startFile;

    // Iterate through lib items recursively (we want lib first so we can
    // potentially override in our own CSS)
    recursive(libDir).forEach((filePath) => {
        // If we've already processed the file, don't do it again
        if (files[filePath] !== undefined) {
            return;
        }

        // Add the file to the files object
        files[filePath] = filePath;

        // Add the CSS content to our mediaQueryCss array
        mediaQueryCss[0] += String(fs.readFileSync(filePath));
    });

    // Iterate through the directories recursively
    recursive(cssLocation).forEach((filePath) => {
        // If we've already processed the file, don't do it again
        if (files[filePath] !== undefined) {
            return;
        }

        // Add the file to the files object
        files[filePath] = filePath;

        // Filename extension
        const ext = path.extname(filePath);

        // If the file extension is not css or pcss, we should ignore it
        if (ext !== '.css' && ext !== '.pcss') {
            return;
        }

        // Determine if file name contains media query as filename.1400.ext
        const pathParts = filePath.split('.');
        const mediaQuery = parseInt(pathParts[pathParts.length - 2], 10) || 0;

        // Add the media query to the array if it doesn't already exist
        if (mediaQueryCss[mediaQuery] === undefined) {
            mediaQueries.push(mediaQuery);
            mediaQueryCss[mediaQuery] = '';
        }

        // Add the CSS content to our mediaQueryCss array
        mediaQueryCss[mediaQuery] += String(fs.readFileSync(filePath));
    });

    // Sort the media queries least to greatest
    const sortedMediaQueries = mediaQueries.sort((a, b) => a - b);

    // Iterate through the sorted media queries, grab the relevant CSS, wrap
    // it in the appropriate media query, and concat it to the cssString
    sortedMediaQueries.forEach((i) => {
        if (i > 0) {
            cssString += `\n@media (min-width: ${i}px) {\n`;
        }

        cssString += mediaQueryCss[i];

        if (i > 0) {
            cssString += '}\n';
        }
    });

    // Process the CSS through postcss with desired plugins
    postcss(
        [
            // Use tailwind
            tailwindcss,
            // Formerly autoprefixer
            postcssPresetEnv({
                features: {
                    'custom-properties': {
                        preserve: false,
                    },
                    /**
                     * Fixes a bug when using with tailwind.
                     * @see https://github.com/tailwindcss/tailwindcss/issues/1190
                     */
                    'focus-within-pseudo-class': false,
                },
            }),
            // Allow us to use hex in RGBA
            hexRGBA,
            // Minifier
            clean({
                level: 2,
            }),
        ],
    )
        .process(cssString, {
            from: undefined,
        })
        .then((result) => {
            // Start the output file with the path
            let cssOutputFile = cssOutputPath;

            // Create the relative file path for the manifest
            let manifestPath = cssOutputFileName;

            // Get a hash of the css content and insert it into the file path
            // for cache breaking
            // Get a hash of the css content
            const md5 = crypto.createHash('md5');
            const hash = `${md5.update(result.css).digest('hex')}`;

            // and insert it into the file path for cache breaking
            cssOutputFile += `/${cssOutputFileName}.${hash}.css`;

            // Update the manifest path
            manifestPath = `${cssOutputFileName}.${hash}.css`;

            // Empty the path
            fs.emptyDirSync(cssOutputPath);

            // If the directory doesn't exist, create it
            if (!fs.existsSync(cssOutputPath)) {
                fs.mkdirSync(cssOutputPath, { recursive: true });
            }

            // Write the file to disk
            fs.writeFileSync(cssOutputFile, result.css);

            fs.writeFileSync(
                `${cssOutputPath}/manifest.json`,
                JSON.stringify({
                    [`${cssOutputFileName}.css`]: manifestPath,
                }, null, 4),
            );

            // All done
            out.success('CSS compiled');
        })
        .catch((error) => {
            console.log(error);
            out.error('There was a PostCSS compile error');
            out.error(`Error: ${error.name}`);
            out.error(`Reason: ${error.reason}`);
            out.error(`Message: ${error.message}`);
            out.error('END PostCSS compile error');
        });
};
