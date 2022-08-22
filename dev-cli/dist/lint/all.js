"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const phpunit_1 = require("./phpunit");
const phpstan_1 = require("./phpstan");
const phpcs_1 = require("./phpcs");
const eslint_1 = require("./eslint");
const tsc_1 = require("./tsc");
const stylelint_1 = require("./stylelint");
class All extends core_1.Command {
    // eslint-disable-next-line class-methods-use-this
    async run() {
        await phpunit_1.default.run();
        await phpstan_1.default.run();
        await phpcs_1.default.runStandAlone(this.config.root);
        await eslint_1.default.runStandAlone(this.config.root);
        await tsc_1.default.run();
        await stylelint_1.default.runStandAlone(this.config.root);
    }
}
exports.default = All;
All.summary = 'Run All linting';
