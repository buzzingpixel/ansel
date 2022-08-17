import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';

export default class MoveEeInstaller extends Command {
    static summary = 'Moves the EE installer so EE will run correctly';

    async run (): Promise<void> {
        execSync(
            `
                cd ${this.config.root}/docker/environments/ee6/vendor/expressionengine/expressionengine/system/ee;
                sudo mv installer installer_back;
            `,
            { stdio: 'inherit' },
        );
    }
}
