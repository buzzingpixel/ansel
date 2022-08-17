"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
class Eslint extends core_1.Command {
    async run() {
        const { flags } = await this.parse(Eslint);
        return Eslint.runStandAlone(this.config.root, flags.fix);
    }
    static async runStandAlone(rootPath, fix) {
        let cmd = 'yarn eslint --ext .js --ext .ts --ext .jsx --ext .tsx --ext .html --ext .vue --ext .mjs --no-error-on-unmatched-pattern assets/js assets/react/app --max-warnings=0';
        if (fix) {
            cmd += ' --fix';
        }
        (0, node_child_process_1.execSync)(`
                cd ${rootPath};
                ${cmd};
            `, { stdio: 'inherit' });
    }
}
exports.default = Eslint;
Eslint.summary = 'Run eslint on project JavaScript files';
Eslint.flags = {
    fix: core_1.Flags.boolean({
        char: 'f',
        default: false,
    }),
};
