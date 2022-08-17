import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';

export default class ComposeConfig extends Command {
    static summary = 'Displays the docker-compose config';

    async run (): Promise<void> {
        execSync(
            `
                cd ${this.config.root}/docker;
                docker compose -f docker-compose.yml config;
            `,
            { stdio: 'inherit' },
        );
    }
}
