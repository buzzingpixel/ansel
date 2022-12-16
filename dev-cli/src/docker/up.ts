import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';
import Craft3 from '../provision/craft3';
import CopyTreasuryDev from '../provision/copy-treasury-dev';
import Ee7 from '../provision/ee7';
import DatabaseSetup from '../provision/database-setup';
import EeCoilpack from '../provision/ee-coilpack';
import EnsureDotEnv from '../pre-flight/ensure-dot-env';

export default class Up extends Command {
    static summary = 'Starts the docker environment for this project';

    async run (): Promise<void> {
        const EnsureDotEnvPreFlight = new EnsureDotEnv(this.argv, this.config);
        await EnsureDotEnvPreFlight.run();

        const CopyTreasuryDevProvision = new CopyTreasuryDev(this.argv, this.config);
        await CopyTreasuryDevProvision.run();

        execSync(
            `
                cd ${this.config.root}/docker;
                docker compose -f docker-compose.yml -p ansel up -d;
            `,
            { stdio: 'inherit' },
        );

        // Set permissions on mounts
        execSync(
            `
                docker exec -w /var/www ansel-php74 bash -c "chmod 0755 ansel";
                docker exec -w /var/www ansel-php80 bash -c "chmod 0755 ansel";
                docker exec -w /var/www ansel-php81 bash -c "chmod 0755 ansel";

                # Craft 3
                docker exec -w /var/www/craft3/config ansel-php74 bash -c "chmod -R 0777 project";
                docker exec -w /var/www/craft3/public ansel-php74 bash -c "chmod -R 0777 cpresources";
                docker exec -w /var/www/craft3/public ansel-php74 bash -c "chmod -R 0777 uploads";
                docker exec -w /var/www/craft3 ansel-php74 bash -c "mkdir -p storage/session";
                docker exec -w /var/www/craft3 ansel-php74 bash -c "chmod -R 0777 storage";

                # EE 7
                docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee7/system/ee";
                docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee7/system/ee";
                docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee7/system/ee";

                docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee7/public/themes/ee";
                docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee7/public/themes/ee";
                docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee7/public/themes/ee";

                docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel";
                docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel";
                docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel";

                docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee7/public/themes/user/ansel";
                docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee7/public/themes/user/ansel";
                docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee7/public/themes/user/ansel";

                docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel/composer.json";
                docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel/composer.json";
                docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel/composer.json";

                docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel/composer.lock";
                docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel/composer.lock";
                docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel/composer.lock";

                docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel/config";
                docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel/config";
                docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel/config";

                docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel/src";
                docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel/src";
                docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel/src";

                docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel/vendor";
                docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel/vendor";
                docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee7/system/user/addons/ansel/vendor";

                docker exec -w /var/www/ee7/public ansel-php74 bash -c "chmod -R 0777 uploads";
                docker exec -w /var/www/ee7/system/user ansel-php74 bash -c "chmod -R 0777 cache";
                docker exec -w /var/www/ee7/system/user/config ansel-php74 bash -c "chmod 0666 config.php";

                # Coilpack
                docker exec -w /var/www/eecoilpack/public ansel-php74 bash -c "chmod -R 0777 uploads";
                docker exec -w /var/www/eecoilpack ansel-php74 bash -c "chmod -R 0777 storage";
                docker exec -w /var/www/eecoilpack/ee/system/user ansel-php74 bash -c "chmod -R 0777 cache";
                docker exec -w /var/www/eecoilpack/ee/system/user/config ansel-php74 bash -c "chmod 0666 config.php";
            `,
            { stdio: 'inherit' },
        );

        const Craft3Provision = new Craft3(this.argv, this.config);
        await Craft3Provision.run();

        const Ee7Provision = new Ee7(this.argv, this.config);
        await Ee7Provision.run();

        const EeCoilpackProvision = new EeCoilpack(this.argv, this.config);
        await EeCoilpackProvision.run();

        const DatabaseSetupProvision = new DatabaseSetup(this.argv, this.config);
        await DatabaseSetupProvision.run();
    }
}
