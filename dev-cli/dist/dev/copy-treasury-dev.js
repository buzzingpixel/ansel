"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const fs = require("fs-extra");
// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');
class CopyTreasuryDev extends core_1.Command {
    async run() {
        const sourceTreasuryDir = `${fs.realpathSync(`${this.config.root}/..`)}/treasury-ee`;
        const sourceTreasuryDirExists = fs.existsSync(sourceTreasuryDir);
        if (!sourceTreasuryDirExists) {
            this.error(style.red('Source Treasury directory does not exist'));
            return;
        }
        const sourceTreasuryDirStats = fs.lstatSync(sourceTreasuryDir);
        if (!sourceTreasuryDirStats || !sourceTreasuryDirStats.isDirectory()) {
            this.error(style.red('Source Treasury directory does not exist'));
            return;
        }
        const workTreasuryDirectory = `${this.config.root}/devResources/treasury-ee`;
        if (fs.existsSync(workTreasuryDirectory)) {
            fs.rmSync(workTreasuryDirectory, {
                recursive: true,
            });
        }
        fs.copySync(sourceTreasuryDir, workTreasuryDirectory);
    }
}
exports.default = CopyTreasuryDev;
CopyTreasuryDev.summary = 'Copies treasury directory into place';
