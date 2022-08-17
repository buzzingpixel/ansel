import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';

// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');

export default class Db extends Command {
    static summary = 'Execute command in `db` container. Empty argument starts a bash session';

    static args = [
        {
            name: 'cmd',
            description: 'command',
            default: null,
        },
    ];

    async run (): Promise<void> {
        const { args } = await this.parse(Db);

        this.log(style.yellow(
            "You're working inside the 'db' container of this project.",
        ));

        if (args.cmd) {
            execSync(
                `
                docker exec -it ansel-db bash -c "${args.cmd}";
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
                docker exec -it ansel-db bash;
            `,
            { stdio: 'inherit' },
        );
    }
}
