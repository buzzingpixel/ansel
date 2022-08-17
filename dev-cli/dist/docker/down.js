"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
class Down extends core_1.Command {
    async run() {
        (0, node_child_process_1.execSync)(`
                cd ${this.config.root}/docker;
                docker compose -f docker-compose.yml -p ansel down;
            `, { stdio: 'inherit' });
    }
}
exports.default = Down;
Down.summary = 'Stops the docker environment for this project';
