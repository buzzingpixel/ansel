import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';

// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');

export default class Php74 extends Command {
    static summary = 'Execute command in `php74` container. Empty argument starts a bash session';

    static args = [
        {
            name: 'cmd',
            description: 'command',
            default: null,
        },
    ];

    async run (): Promise<void> {
        const { args } = await this.parse(Php74);

        this.log(style.yellow(
            "You're working inside the 'php74' container of this project.",
        ));

        if (args.cmd) {
            execSync(
                `
                docker exec -it -w /var/www ansel-php74 bash -c "XDEBUG_MODE=off ${args.cmd}";
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
                docker exec -it -e XDEBUG_MODE=off -w /var/www ansel-php74 bash;
            `,
            { stdio: 'inherit' },
        );
    }
}
