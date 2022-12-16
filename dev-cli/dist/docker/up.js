"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
const craft3_1 = require("../provision/craft3");
const copy_treasury_dev_1 = require("../provision/copy-treasury-dev");
const ee7_1 = require("../provision/ee7");
const database_setup_1 = require("../provision/database-setup");
const ee_coilpack_1 = require("../provision/ee-coilpack");
const ensure_dot_env_1 = require("../pre-flight/ensure-dot-env");
class Up extends core_1.Command {
    async run() {
        const EnsureDotEnvPreFlight = new ensure_dot_env_1.default(this.argv, this.config);
        await EnsureDotEnvPreFlight.run();
        const CopyTreasuryDevProvision = new copy_treasury_dev_1.default(this.argv, this.config);
        await CopyTreasuryDevProvision.run();
        (0, node_child_process_1.execSync)(`
                cd ${this.config.root}/docker;
                docker compose -f docker-compose.yml -p ansel up -d;
            `, { stdio: 'inherit' });
        // Set permissions on mounts
        (0, node_child_process_1.execSync)(`
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
            `, { stdio: 'inherit' });
        const Craft3Provision = new craft3_1.default(this.argv, this.config);
        await Craft3Provision.run();
        const Ee7Provision = new ee7_1.default(this.argv, this.config);
        await Ee7Provision.run();
        const EeCoilpackProvision = new ee_coilpack_1.default(this.argv, this.config);
        await EeCoilpackProvision.run();
        const DatabaseSetupProvision = new database_setup_1.default(this.argv, this.config);
        await DatabaseSetupProvision.run();
    }
}
exports.default = Up;
Up.summary = 'Starts the docker environment for this project';
