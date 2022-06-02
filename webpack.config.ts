// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-ignore
import path from 'path';
import { Configuration } from 'webpack';
import { WebpackManifestPlugin } from 'webpack-manifest-plugin';
// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-ignore
import TerserPlugin from 'terser-webpack-plugin';
import yargs from 'yargs';
import { hideBin } from 'yargs/helpers';

const { argv } = yargs(hideBin(process.argv));

const mode = argv.mode || 'production';

const config: Configuration = {
    cache: {
        type: 'filesystem',
    },
    entry: {
        'ansel.min': './assets/js/ansel.ts',
    },
    module: {
        rules: [
            {
                test: /\.(ts|js)x?$/,
                exclude: /node_modules/,
                use: {
                    loader: 'esbuild-loader',
                    options: {
                        loader: 'tsx',
                        target: 'es2015',
                    },
                },
            },
        ],
    },
    resolve: {
        extensions: ['.tsx', '.ts', '.jsx', '.js'],
    },
    output: {
        clean: true,
        path: path.resolve(__dirname, 'assetsDist/js'),
        filename: 'ansel.min.[contenthash].js',
        sourceMapFilename: 'ansel.min.map',
    },
    optimization: {
        minimizer: [new TerserPlugin({ extractComments: false })],
    },
    plugins: [
        new WebpackManifestPlugin({
            publicPath: '',
        }),
    ],
    performance: {
        hints: false,
        maxEntrypointSize: 512000,
        maxAssetSize: 512000,
    },
};

if (mode === 'development') {
    config.devtool = 'eval-source-map';
}

export default config;
