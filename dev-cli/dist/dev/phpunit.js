"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
class Phpunit extends core_1.Command {
    async run() {
        (0, node_child_process_1.execSync)(`
                cd ${this.config.root};
                XDEBUG_MODE=coverage php74 ./vendor/bin/phpunit;
            `, { stdio: 'inherit' });
    }
}
exports.default = Phpunit;
Phpunit.summary = 'Run PHPUnit on project PHP files';
