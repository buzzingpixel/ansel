import fs from 'fs-extra';
import out from 'cli-output';
import recursive from 'recursive-readdir-sync';
import crypto from 'crypto';
import path from 'path';
import bable from '@babel/core';
import {minify} from "terser";
import ts from 'typescript';

const appDir = process.cwd();
const sourceDir = `${appDir}/assets/react`;
const outputDir = `${appDir}/assetsDist/react`;
const manifestLocation = `${outputDir}/manifest.json`;

const minifyOptions = {
    sourceMap: false,
    mangle: true,
    compress: true,
    output: {
        beautify: false,
    },
};

let manifestObject = {};

const copySourceFile = (filePath, pathInsert) => {
    let relativePath = filePath.slice(
        filePath.indexOf(sourceDir) + 1 + sourceDir.length,
    );

    const relativePathInitial = relativePath;

    if (pathInsert) {
        relativePath = `${pathInsert}/${relativePath}`;
    }

    const outputFullPath = `${outputDir}/${relativePath}`;

    // If the file no longer exists at the source, delete it
    if (!fs.existsSync(filePath)) {
        delete manifestObject[relativePathInitial];

        if (fs.existsSync(outputFullPath)) {
            fs.removeSync(outputFullPath);
        }

        // Write the manifest output
        manifestObject[relativePathInitial] = relativePath;

        fs.writeFileSync(
            manifestLocation,
            JSON.stringify(manifestObject, null, 4),
        );

        return;
    }

    manifestObject[relativePathInitial] = relativePath;

    fs.copySync(filePath, outputFullPath);

    fs.writeFileSync(
        manifestLocation,
        JSON.stringify(manifestObject, null, 4),
    );
}

const processAppFile = async (filePath, pathInsert) => {
    const ext = path.extname(filePath);

    let relativePath = filePath.slice(
        filePath.indexOf(sourceDir) + 1 + sourceDir.length,
    );

    const relativePathInitial = relativePath;

    if (pathInsert) {
        relativePath = `${pathInsert}/${relativePath}`;
    }

    const outputFullPath = `${outputDir}/${relativePath}`;
    const outputFullPathDir = path.dirname(outputFullPath);
    const relativeReplacer = appDir + path.sep;
    const relativeName = filePath
        .slice(filePath.indexOf(relativeReplacer) + relativeReplacer.length)
        .split(path.sep)
        .join('/');
    const code = {};

    // If the file no longer exists at the source, delete it
    if (!fs.existsSync(filePath)) {
        delete manifestObject[relativePathInitial];

        if (fs.existsSync(outputFullPath)) {
            fs.removeSync(outputFullPath);
        }

        // Write the manifest output
        manifestObject[relativePathInitial] = relativePath;

        fs.writeFileSync(
            manifestLocation,
            JSON.stringify(manifestObject, null, 4),
        );

        return;
    }

    // We can only process these types currently
    if (ext !== '.jsx' && ext !== '.js' && ext !== '.tsx' && ext !== '.ts') {
        return;
    }

    // Process the js content
    code[relativeName] = String(fs.readFileSync(filePath));

    if (ext === '.tsx' || ext === '.ts') {

        let result = ts.transpileModule(
            code[relativeName],
            {
                compilerOptions: {
                    module: ts.ModuleKind.CommonJS,
                    checkJs: true,
                    jsx: ts.JsxEmit.Preserve,
                    strict: true,
                },
            }
        );

        code[relativeName] = result.outputText;
    }

    if (ext === '.jsx' || ext === '.tsx') {
        const parsed = bable.transformSync(code[relativeName], {
            presets: [
                '@babel/preset-env',
                '@babel/preset-react'
            ]
        });

        code[relativeName] = parsed.code;
    }

    try {
        const processed = await minify(code, minifyOptions);

        // Create directory if it doesn't exist
        if (!fs.existsSync(outputFullPathDir)) {
            fs.mkdirSync(outputFullPathDir, { recursive: true });
        }

        // Write the JS file to disk
        fs.writeFileSync(outputFullPath, processed.code);

        // Write the manifest output
        manifestObject[relativePathInitial] = relativePath;

        fs.writeFileSync(
            manifestLocation,
            JSON.stringify(manifestObject, null, 4),
        );
    } catch (error) {
        out.error('There was an error compiling Javascript');
        out.error(`Error: ${error.message}`);
        out.error(`File: ${filePath}`);
        out.error(`Line: ${error.line}`);
        out.error(`Column: ${error.col}`);
        out.error(`Position: ${error.pos}`);
        out.log('=====================================================');
    }
}

export default async (prod) => {
    // Let the user know what we're doing
    out.info('Compiling React...');

    // Empty the JS dir first
    fs.emptyDirSync(outputDir);

    // We'll create a hash of the JS to make a path insert for cache breaking
    // if we're in prod mode
    let pathInsert = '';

    if (prod === true) {
        let jsString = '';

        // Recursively iterate through the files in the JS location
        recursive(sourceDir).forEach((filePath) => {
            jsString += filePath + String(fs.readFileSync(filePath));
        });

        // Get a hash of the js content and set it to the pathInsert variable
        const md5 = crypto.createHash('md5');
        pathInsert = `${md5.update(jsString).digest('hex')}`;
    }

    // Create the manifest file
    fs.writeFileSync(
        manifestLocation,
        JSON.stringify({}, null, 4),
    );

    // Recursively iterate through React source files
    recursive(sourceDir + '/src').forEach((filePath) => {
        // Send the file for processing
        copySourceFile(filePath, pathInsert);
    });

    // Recursively iterate through app source file
    await recursive(sourceDir + '/app').forEach((filePath) => {
        // Send the file for processing
        processAppFile(filePath, pathInsert);
    });

    out.success('React compiled');
}
