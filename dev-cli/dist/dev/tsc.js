"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
class Tsc extends core_1.Command {
    async run() {
        (0, node_child_process_1.execSync)(`
                cd ${this.config.root};
                yarn tsc;
            `, { stdio: 'inherit' });
    }
}
exports.default = Tsc;
Tsc.summary = 'Run typescript checking';
