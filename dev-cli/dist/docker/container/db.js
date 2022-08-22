"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');
class Db extends core_1.Command {
    async run() {
        const { args } = await this.parse(Db);
        this.log(style.yellow("You're working inside the 'db' container of this project."));
        if (args.cmd) {
            (0, node_child_process_1.execSync)(`
                docker exec -it ansel-db bash -c "${args.cmd}";
            `, { stdio: 'inherit' });
            return;
        }
        this.log(style.yellow("Remember to exit when you're done"));
        (0, node_child_process_1.execSync)(`
                docker exec -it ansel-db bash;
            `, { stdio: 'inherit' });
    }
}
exports.default = Db;
Db.summary = 'Execute command in `db` container. Empty argument starts a bash session';
Db.args = [
    {
        name: 'cmd',
        description: 'command',
        default: null,
    },
];
