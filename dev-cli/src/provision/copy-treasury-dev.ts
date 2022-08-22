import { Command } from '@oclif/core';
import * as fs from 'fs-extra';

// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');

export default class CopyTreasuryDev extends Command {
    static summary = 'Copies treasury directory into place';

    async run (): Promise<void> {
        const sourceTreasuryDir = `${fs.realpathSync(
            `${this.config.root}/..`,
        )}/treasury-ee`;

        const sourceTreasuryDirExists = fs.existsSync(sourceTreasuryDir);

        if (!sourceTreasuryDirExists) {
            this.error(
                style.red('Source Treasury directory does not exist'),
            );

            return;
        }

        const sourceTreasuryDirStats = fs.lstatSync(sourceTreasuryDir);

        if (!sourceTreasuryDirStats || !sourceTreasuryDirStats.isDirectory()) {
            this.error(
                style.red('Source Treasury directory does not exist'),
            );

            return;
        }

        const workTreasuryDirectory = `${this.config.root}/devResources/treasury-ee`;

        if (fs.existsSync(workTreasuryDirectory)) {
            fs.rmSync(workTreasuryDirectory, {
                recursive: true,
            });
        }

        fs.copySync(sourceTreasuryDir, workTreasuryDirectory);
    }
}
