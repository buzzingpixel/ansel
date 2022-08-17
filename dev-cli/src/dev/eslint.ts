import { Command, Flags } from '@oclif/core';
import { execSync } from 'node:child_process';

export default class Eslint extends Command {
    static summary = 'Run eslint on project JavaScript files';

    static flags = {
        fix: Flags.boolean({
            char: 'f',
            default: false,
        }),
    };

    async run (): Promise<void> {
        const { flags } = await this.parse(Eslint);

        return Eslint.runStandAlone(this.config.root, flags.fix);
    }

    public static async runStandAlone (
        rootPath: string,
        fix?: boolean,
    ) {
        let cmd = 'yarn eslint --ext .js --ext .ts --ext .jsx --ext .tsx --ext .html --ext .vue --ext .mjs --no-error-on-unmatched-pattern assets/js assets/react/app --max-warnings=0';

        if (fix) {
            cmd += ' --fix';
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
