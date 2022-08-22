import { Command } from '@oclif/core';
import phpunit from './phpunit';
import phpstan from './phpstan';
import phpcs from './phpcs';
import eslint from './eslint';
import tsc from './tsc';
import stylelint from './stylelint';

export default class All extends Command {
    static summary = 'Run All linting';

    // eslint-disable-next-line class-methods-use-this
    async run (): Promise<void> {
        await phpunit.run();
        await phpstan.run();
        await phpcs.runStandAlone(this.config.root);
        await eslint.runStandAlone(this.config.root);
        await tsc.run();
        await stylelint.runStandAlone(this.config.root);
    }
}
