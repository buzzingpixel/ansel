import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';

// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');

export default class Nginx extends Command {
    static summary = 'Execute command in `nginx` container. Empty argument starts a bash session';

    static args = [
        {
            name: 'cmd',
            description: 'command',
            default: null,
        },
    ];

    async run (): Promise<void> {
        const { args } = await this.parse(Nginx);

        this.log(style.yellow(
            "You're working inside the 'ngnix' container of this project.",
        ));

        if (args.cmd) {
            await this.runCommand(args.cmd);

            return;
        }

        this.log(style.yellow(
            "Remember to exit when you're done",
        ));

        execSync(
            `
                docker exec -it -e XDEBUG_MODE=off -w /var/www ansel-nginx bash;
            `,
            { stdio: 'inherit' },
        );
    }

    // eslint-disable-next-line class-methods-use-this
    async runCommand (cmd: string): Promise<void> {
        execSync(
            `
                docker exec -it -w /var/www ansel-nginx bash -c "XDEBUG_MODE=off ${cmd}";
            `,
            { stdio: 'inherit' },
        );
    }
}
