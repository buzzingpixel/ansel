import { Command } from '@oclif/core';
import * as fs from 'fs';

// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');

export default class EnsureDotEnv extends Command {
    static summary = 'Ensures .env file exists';

    static hidden = true;

    async run (): Promise<void> {
        const envFilePath = `${this.config.root}/docker/.env`;

        if (fs.existsSync(envFilePath)) {
            return;
        }

        throw new Error(style.red(
            `Please create a .env file from .env.example at ${envFilePath}`,
        ));
    }
}
