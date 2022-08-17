import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';

export default class Tsc extends Command {
    static summary = 'Run typescript checking';

    async run (): Promise<void> {
        execSync(
            `
                cd ${this.config.root};
                yarn tsc;
            `,
            { stdio: 'inherit' },
        );
    }
}
