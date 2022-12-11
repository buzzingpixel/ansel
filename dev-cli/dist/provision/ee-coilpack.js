"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const php80_1 = require("../docker/container/php80");
// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');
class EeCoilpack extends core_1.Command {
    async run() {
        this.log(style.yellow('Running provisioning for EE Coilpack...'));
        const Php80Container = new php80_1.default(this.argv, this.config);
        await Php80Container.runCommand('cd /var/www/eecoilpack && composer install --no-interaction --no-ansi --no-progress');
        this.log(style.cyan('Done.'));
    }
}
exports.default = EeCoilpack;
EeCoilpack.summary = 'Runs provisioning for the EE Coilpack environment';
