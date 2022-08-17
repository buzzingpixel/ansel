"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
class Stylelint extends core_1.Command {
    async run() {
        const { flags } = await this.parse(Stylelint);
        return Stylelint.runStandAlone(this.config.root, flags.fix);
    }
    static async runStandAlone(rootPath, fix) {
        let cmd = 'yarn stylelint --allow-empty-input "assets/**/*.{css,pcss}" "src/**/*.{css,pcss}"';
        if (fix) {
            cmd += ' --fix';
        }
        (0, node_child_process_1.execSync)(`
                cd ${rootPath};
                ${cmd};
            `, { stdio: 'inherit' });
    }
}
exports.default = Stylelint;
Stylelint.summary = 'Run Stylelint checking';
Stylelint.flags = {
    fix: core_1.Flags.boolean({
        char: 'f',
        default: false,
    }),
};
