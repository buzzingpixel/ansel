import { Command } from '@oclif/core';
import Php80 from '../docker/container/php80';

// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');

export default class EeCoilpack extends Command {
    static summary = 'Runs provisioning for the EE Coilpack environment';

    async run (): Promise<void> {
        this.log(style.yellow('Running provisioning for EE Coilpack...'));

        const Php80Container = new Php80(this.argv, this.config);

        await Php80Container.runCommand(
            'cd /var/www/eecoilpack && composer install --no-interaction --no-ansi --no-progress',
        );

        this.log(style.cyan('Done.'));
    }
}
