import out from 'cli-output';
import fs from 'fs';
import crypto from 'crypto';
import { Parcel } from '@parcel/core';
import fsExtra from 'fs-extra';

const appDir = process.cwd();
const sourceDir = `${appDir}/assets/js`;
const outputDir = `${appDir}/assetsDist/js`;
const manifestLocation = `${outputDir}/manifest.json`;

const finish = () => {
    // Get the contents of our intermediate file
    const jsString = String(fs.readFileSync(`${outputDir}/ansel.min.js`));

    // Get a hash of the js content and set it to the pathInsert variable
    const md5 = crypto.createHash('md5');
    const pathInsert = `${md5.update(jsString).digest('hex')}`;

    fs.mkdirSync(`${outputDir}/${pathInsert}`);

    fs.renameSync(
        `${outputDir}/ansel.min.js`,
        `${outputDir}/${pathInsert}/ansel.min.js`,
    );

    fs.renameSync(
        `${outputDir}/ansel.min.js.map`,
        `${outputDir}/${pathInsert}/ansel.min.js.map`,
    );

    fs.writeFileSync(
        manifestLocation,
        JSON.stringify({
            'ansel.min.js': `${pathInsert}/ansel.min.js`,
        }, null, 4),
    );

    out.success('JS compiled');
};

const dataStore = {
    hasStarted: false,
    tries: 0,
};

const runBundler = async () => {
    const bundler = new Parcel({
        entries: `${sourceDir}/ansel.ts`,
        defaultConfig: '@parcel/config-default',
        mode: 'production',
        env: {
            NODE_ENV: 'production',
        },
    });

    await bundler.run();
};

const js = async () => {
    if (!dataStore.hasStarted) {
        out.info('Compiling JS...');

        dataStore.hasStarted = true;
    }

    // Empty the output dir
    fsExtra.emptyDirSync(outputDir);

    runBundler()
        .then(() => {
            finish();
        })
        .catch((error) => {
            if (dataStore.tries > 100) {
                throw error;
            }

            dataStore.tries += 1;

            js();
        });
};

export default js;
