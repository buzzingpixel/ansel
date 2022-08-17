"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
class Phpcs extends core_1.Command {
    async run() {
        const { flags } = await this.parse(Phpcs);
        return Phpcs.runStandAlone(this.config.root, flags.fix);
    }
    static async runStandAlone(rootPath, fix) {
        let cmd = 'php74 -d memory_limit=4G ./vendor/bin/';
        if (fix) {
            cmd += 'phpcbf';
        }
        else {
            cmd += 'phpcs';
        }
        (0, node_child_process_1.execSync)(`
                cd ${rootPath};
                ${cmd};
            `, { stdio: 'inherit' });
    }
}
exports.default = Phpcs;
Phpcs.summary = 'Run PHPCS on project PHP files';
Phpcs.flags = {
    fix: core_1.Flags.boolean({
        char: 'f',
        default: false,
    }),
};
