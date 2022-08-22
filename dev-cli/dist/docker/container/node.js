"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');
class Node extends core_1.Command {
    async run() {
        const { args } = await this.parse(Node);
        this.log(style.yellow("You're working inside the 'node' container of this project."));
        if (args.cmd) {
            (0, node_child_process_1.execSync)(`
                docker run --rm -it \
                    --name ansel-node \
                    -v ${this.config.root}:/app \
                    -w /app \
                    ansel-node sh -c "${args.cmd}";
            `, { stdio: 'inherit' });
            return;
        }
        this.log(style.yellow("Remember to exit when you're done"));
        (0, node_child_process_1.execSync)(`
                docker run --rm -it \\
                    --name ansel-node \\
                    -v ${this.config.root}:/app \\
                    -w /app \\
                    ansel-node sh;
            `, { stdio: 'inherit' });
    }
}
exports.default = Node;
Node.summary = 'Execute command in `node` container. Empty argument starts a bash session';
Node.args = [
    {
        name: 'cmd',
        description: 'command',
        default: null,
    },
];
