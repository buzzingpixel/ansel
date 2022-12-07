"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
class Up extends core_1.Command {
    async run() {
        // EE 7 Pre-flight
        (0, node_child_process_1.execSync)(`
                docker run --rm --entrypoint "" -v ${this.config.root}/docker/environments/ee7:/var/www/ee7 -w /var/www/ee7 ansel_ansel-php74 bash -c "XDEBUG_MODE=off composer install --no-interaction --no-ansi --no-progress";
            `, { stdio: 'inherit' });
        // Up the env
        (0, node_child_process_1.execSync)(`
                cd ${this.config.root}/docker;
                docker compose -f docker-compose.yml -p ansel up -d;
            `, { stdio: 'inherit' });
        // Database setup
        (0, node_child_process_1.execSync)(`
                chmod +x ${this.config.root}/dev-cli/scripts/database-setup.sh;
                ${this.config.root}/dev-cli/scripts/database-setup.sh;
            `, { stdio: 'inherit' });
        // Set permissions on mounts
        (0, node_child_process_1.execSync)(`
                docker exec -w /var/www ansel-php74 bash -c "chmod 0755 ansel";
                docker exec -w /var/www ansel-php80 bash -c "chmod 0755 ansel";
                docker exec -w /var/www ansel-php81 bash -c "chmod 0755 ansel";

                # Craft 3
                docker exec -w /var/www/craft3 ansel-php74 bash -c "composer install";
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
            `, { stdio: 'inherit' });
    }
}
exports.default = Up;
Up.summary = 'Starts the docker environment for this project';
