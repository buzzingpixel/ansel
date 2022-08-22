import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';

export default class Phpstan extends Command {
    static summary = 'Run PHPStan on project PHP files';

    async run (): Promise<void> {
        execSync(
            `
                cd ${this.config.root};
                XDEBUG_MODE=off php74 -d memory_limit=4G ./vendor/bin/phpstan analyse cms src;
            `,
            { stdio: 'inherit' },
        );
    }
}
