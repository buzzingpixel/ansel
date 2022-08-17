"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
class MoveEeInstaller extends core_1.Command {
    async run() {
        (0, node_child_process_1.execSync)(`
                cd ${this.config.root}/docker/environments/ee6/vendor/expressionengine/expressionengine/system/ee;
                sudo mv installer installer_back;
            `, { stdio: 'inherit' });
    }
}
exports.default = MoveEeInstaller;
MoveEeInstaller.summary = 'Moves the EE installer so EE will run correctly';
