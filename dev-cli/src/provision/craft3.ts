import { Command } from '@oclif/core';
import Php74 from '../docker/container/php74';

// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');

export default class Craft3 extends Command {
    static summary = 'Runs provisioning for the Craft 3 environment';

    async run (): Promise<void> {
        this.log(style.yellow('Running provisioning for Craft 3...'));

        const Php74Container = new Php74(this.argv, this.config);

        await Php74Container.runCommand(
            'cd /var/www/craft3 && composer install --no-interaction --no-ansi --no-progress',
        );

        this.log(style.cyan('Done.'));
    }
}
