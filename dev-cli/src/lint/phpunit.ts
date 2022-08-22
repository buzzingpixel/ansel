import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';

export default class Phpunit extends Command {
    static summary = 'Run PHPUnit on project PHP files';

    async run (): Promise<void> {
        execSync(
            `
                cd ${this.config.root};
                XDEBUG_MODE=coverage php74 ./vendor/bin/phpunit;
            `,
            { stdio: 'inherit' },
        );
    }
}
