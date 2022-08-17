import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';

export default class Down extends Command {
    static summary = 'Stops the docker environment for this project';

    async run (): Promise<void> {
        execSync(
            `
                cd ${this.config.root}/docker;
                docker compose -f docker-compose.yml -p ansel down;
            `,
            { stdio: 'inherit' },
        );
    }
}
