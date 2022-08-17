import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';

// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');

export default class Node extends Command {
    static summary = 'Execute command in `node` container. Empty argument starts a bash session';

    static args = [
        {
            name: 'cmd',
            description: 'command',
            default: null,
        },
    ];

    async run (): Promise<void> {
        const { args } = await this.parse(Node);

        this.log(style.yellow(
            "You're working inside the 'node' container of this project.",
        ));

        if (args.cmd) {
            execSync(
                `
                docker run --rm -it \
                    --name ansel-node \
                    -v ${this.config.root}:/app \
                    -w /app \
                    ansel-node sh -c "${args.cmd}";
            `,
                { stdio: 'inherit' },
            );

            return;
        }

        this.log(style.yellow(
            "Remember to exit when you're done",
        ));

        execSync(
            `
                docker run --rm -it \\
                    --name ansel-node \\
                    -v ${this.config.root}:/app \\
                    -w /app \\
                    ansel-node sh;
            `,
            { stdio: 'inherit' },
        );
    }
}
