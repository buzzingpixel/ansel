"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const fs = require("fs");
// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');
class EnsureDotEnv extends core_1.Command {
    async run() {
        const envFilePath = `${this.config.root}/docker/.env`;
        if (fs.existsSync(envFilePath)) {
            return;
        }
        throw new Error(style.red(`Please create a .env file from .env.example at ${envFilePath}`));
    }
}
exports.default = EnsureDotEnv;
EnsureDotEnv.summary = 'Ensures .env file exists';
EnsureDotEnv.hidden = true;
