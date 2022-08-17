"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
class Phpstan extends core_1.Command {
    async run() {
        (0, node_child_process_1.execSync)(`
                cd ${this.config.root};
                XDEBUG_MODE=off php74 -d memory_limit=4G ./vendor/bin/phpstan analyse cms src;
            `, { stdio: 'inherit' });
    }
}
exports.default = Phpstan;
Phpstan.summary = 'Run PHPStan on project PHP files';
