"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');
class Nginx extends core_1.Command {
    async run() {
        const { args } = await this.parse(Nginx);
        this.log(style.yellow("You're working inside the 'ngnix' container of this project."));
        if (args.cmd) {
            await this.runCommand(args.cmd);
            return;
        }
        this.log(style.yellow("Remember to exit when you're done"));
        (0, node_child_process_1.execSync)(`
                docker exec -it -e XDEBUG_MODE=off -w /var/www ansel-nginx bash;
            `, { stdio: 'inherit' });
    }
    // eslint-disable-next-line class-methods-use-this
    async runCommand(cmd) {
        (0, node_child_process_1.execSync)(`
                docker exec -it -w /var/www ansel-nginx bash -c "XDEBUG_MODE=off ${cmd}";
            `, { stdio: 'inherit' });
    }
}
exports.default = Nginx;
Nginx.summary = 'Execute command in `nginx` container. Empty argument starts a bash session';
Nginx.args = [
    {
        name: 'cmd',
        description: 'command',
        default: null,
    },
];
