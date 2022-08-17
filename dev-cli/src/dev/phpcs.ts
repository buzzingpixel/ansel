import { Command, Flags } from '@oclif/core';
import { execSync } from 'node:child_process';

export default class Phpcs extends Command {
    public static summary = 'Run PHPCS on project PHP files';

    public static flags = {
        fix: Flags.boolean({
            char: 'f',
            default: false,
        }),
    };

    public async run (): Promise<void> {
        const { flags } = await this.parse(Phpcs);

        return Phpcs.runStandAlone(this.config.root, flags.fix);
    }

    public static async runStandAlone (
        rootPath: string,
        fix?: boolean,
    ) {
        let cmd = 'php74 -d memory_limit=4G ./vendor/bin/';

        if (fix) {
            cmd += 'phpcbf';
        } else {
            cmd += 'phpcs';
        }

        execSync(
            `
                cd ${rootPath};
                ${cmd};
            `,
            { stdio: 'inherit' },
        );
    }
}
