"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
class ComposeConfig extends core_1.Command {
    async run() {
        (0, node_child_process_1.execSync)(`
                cd ${this.config.root}/docker;
                docker compose -f docker-compose.yml config;
            `, { stdio: 'inherit' });
    }
}
exports.default = ComposeConfig;
ComposeConfig.summary = 'Displays the docker-compose config';
