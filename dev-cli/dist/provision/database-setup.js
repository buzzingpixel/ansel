"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');
class DatabaseSetup extends core_1.Command {
    async run() {
        this.log(style.yellow('Ensuring docker databases exist...'));
        // Database setup
        (0, node_child_process_1.execSync)(`
                chmod +x ${this.config.root}/dev-cli/scripts/database-setup.sh;
                ${this.config.root}/dev-cli/scripts/database-setup.sh;
            `, { stdio: 'inherit' });
        this.log(style.cyan('Done.'));
    }
}
exports.default = DatabaseSetup;
DatabaseSetup.summary = 'Creates Docker databases';
