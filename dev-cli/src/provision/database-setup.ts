import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';

// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');

export default class DatabaseSetup extends Command {
    static summary = 'Creates Docker databases';

    async run (): Promise<void> {
        this.log(style.yellow('Ensuring docker databases exist...'));

        // Database setup
        execSync(
            `
                chmod +x ${this.config.root}/dev-cli/scripts/database-setup.sh;
                ${this.config.root}/dev-cli/scripts/database-setup.sh;
            `,
            { stdio: 'inherit' },
        );

        this.log(style.cyan('Done.'));
    }
}
