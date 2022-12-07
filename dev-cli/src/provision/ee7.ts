import { Command } from '@oclif/core';
import Php74 from '../docker/container/php74';

// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');

export default class Ee7 extends Command {
    static summary = 'Runs provisioning for the EE 7 environment';

    async run (): Promise<void> {
        this.log(style.yellow('Running provisioning for EE 7...'));

        const Php74Container = new Php74(this.argv, this.config);

        await Php74Container.runCommand(
            'cd /var/www/ee7 && composer install --no-interaction --no-ansi --no-progress',
        );

        this.log(style.cyan('Done.'));
    }
}
