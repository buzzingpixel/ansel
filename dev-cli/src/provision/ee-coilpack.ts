import { Command } from '@oclif/core';
import Php81 from '../docker/container/php81';

// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');

export default class EeCoilpack extends Command {
    static summary = 'Runs provisioning for the EE Coilpack environment';

    async run (): Promise<void> {
        this.log(style.yellow('Running provisioning for EE Coilpack...'));

        const Php81Container = new Php81(this.argv, this.config);

        await Php81Container.runCommand(
            'cd /var/www/eecoilpack && composer install --no-interaction --no-ansi --no-progress',
        );

        this.log(style.cyan('Done.'));
    }
}
