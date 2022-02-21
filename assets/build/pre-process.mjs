import fsExtra from 'fs-extra';

const appDir = process.cwd();
const outputDir = `${appDir}/assetsDist/js`;
const intermediateDir = `${appDir}/assetsIntermediate/js`;

export default () => {
    // Empty the output dir
    fs.emptyDirSync(outputDir);

    // Empty the intermediate dir
    fs.emptyDirSync(intermediateDir);
};
