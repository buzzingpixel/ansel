import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';

// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');

export default class CreateDatabases extends Command {
    static summary = 'Creates Docker databases';

    async run (): Promise<void> {
        this.log(style.yellow('Ensuring docker databases exist...'));

        // ExpressionEngine
        execSync(
            `
                docker exec ansel-db bash -c "mysql -uroot -proot -e \\"CREATE DATABASE IF NOT EXISTS anselee\\"";
            `,
            { stdio: 'inherit' },
        );
        execSync(
            `
                docker exec ansel-db bash -c "mysql -uroot -proot -e \\"CREATE USER IF NOT EXISTS 'anselee'@'%' IDENTIFIED BY 'secret'\\"";
            `,
            { stdio: 'inherit' },
        );
        execSync(
            `
                docker exec ansel-db bash -c "mysql -uroot -proot -e \\"GRANT ALL on anselee.* to 'anselee'@'%'\\"";
            `,
            { stdio: 'inherit' },
        );

        // Craft
        execSync(
            `
                docker exec ansel-db bash -c "mysql -uroot -proot -e \\"CREATE DATABASE IF NOT EXISTS anselcraft\\"";
            `,
            { stdio: 'inherit' },
        );
        execSync(
            `
                docker exec ansel-db bash -c "mysql -uroot -proot -e \\"CREATE USER IF NOT EXISTS 'anselcraft'@'%' IDENTIFIED BY 'secret'\\"";
            `,
            { stdio: 'inherit' },
        );
        execSync(
            `
                docker exec ansel-db bash -c "mysql -uroot -proot -e \\"GRANT ALL on anselcraft.* to 'anselcraft'@'%'\\"";
            `,
            { stdio: 'inherit' },
        );

        this.log(style.cyan('Done.'));
    }
}
