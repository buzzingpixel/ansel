"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const php74_1 = require("../docker/container/php74");
// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');
class Ee7 extends core_1.Command {
    async run() {
        this.log(style.yellow('Running provisioning for EE 7...'));
        const Php74Container = new php74_1.default(this.argv, this.config);
        await Php74Container.runCommand('cd /var/www/ee7 && composer install --no-interaction --no-ansi --no-progress');
        this.log(style.cyan('Done.'));
    }
}
exports.default = Ee7;
Ee7.summary = 'Runs provisioning for the EE 7 environment';
