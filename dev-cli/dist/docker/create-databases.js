"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');
class CreateDatabases extends core_1.Command {
    async run() {
        this.log(style.yellow('Ensuring docker databases exist...'));
        // ExpressionEngine
        (0, node_child_process_1.execSync)(`
                docker exec ansel-db bash -c "mysql -uroot -proot -e \\"CREATE DATABASE IF NOT EXISTS anselee\\"";
            `, { stdio: 'inherit' });
        (0, node_child_process_1.execSync)(`
                docker exec ansel-db bash -c "mysql -uroot -proot -e \\"CREATE USER IF NOT EXISTS 'anselee'@'%' IDENTIFIED BY 'secret'\\"";
            `, { stdio: 'inherit' });
        (0, node_child_process_1.execSync)(`
                docker exec ansel-db bash -c "mysql -uroot -proot -e \\"GRANT ALL on anselee.* to 'anselee'@'%'\\"";
            `, { stdio: 'inherit' });
        // Craft
        (0, node_child_process_1.execSync)(`
                docker exec ansel-db bash -c "mysql -uroot -proot -e \\"CREATE DATABASE IF NOT EXISTS anselcraft\\"";
            `, { stdio: 'inherit' });
        (0, node_child_process_1.execSync)(`
                docker exec ansel-db bash -c "mysql -uroot -proot -e \\"CREATE USER IF NOT EXISTS 'anselcraft'@'%' IDENTIFIED BY 'secret'\\"";
            `, { stdio: 'inherit' });
        (0, node_child_process_1.execSync)(`
                docker exec ansel-db bash -c "mysql -uroot -proot -e \\"GRANT ALL on anselcraft.* to 'anselcraft'@'%'\\"";
            `, { stdio: 'inherit' });
        this.log(style.cyan('Done.'));
    }
}
exports.default = CreateDatabases;
CreateDatabases.summary = 'Creates Docker databases';
