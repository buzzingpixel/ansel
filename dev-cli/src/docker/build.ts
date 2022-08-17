import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';

export default class Build extends Command {
    static summary = 'Build Docker images for project';

    async run (): Promise<void> {
        execSync(
            `
                cd ${this.config.root}/docker;
                docker compose -f docker-compose.yml -p ansel build;
                docker build . --tag ansel-node --file ./node/Dockerfile;
            `,
            { stdio: 'inherit' },
        );
    }
}
