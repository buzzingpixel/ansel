import { Command, Flags } from '@oclif/core';
import { execSync } from 'node:child_process';

export default class Stylelint extends Command {
    static summary = 'Run Stylelint checking';

    static flags = {
        fix: Flags.boolean({
            char: 'f',
            default: false,
        }),
    };

    async run (): Promise<void> {
        const { flags } = await this.parse(Stylelint);

        return Stylelint.runStandAlone(this.config.root, flags.fix);
    }

    public static async runStandAlone (
        rootPath: string,
        fix?: boolean,
    ) {
        let cmd = 'yarn stylelint --allow-empty-input "assets/**/*.{css,pcss}" "src/**/*.{css,pcss}"';

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
