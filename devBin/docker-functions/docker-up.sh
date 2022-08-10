#!/usr/bin/env bash

function docker-up() {
    START_DIR=$(pwd);
    cd ${START_DIR}/docker;

    # EE 6 Pre-flight
    docker run --rm --entrypoint "" -v "${PWD}"/environments/ee6:/var/www/ee6 -w /var/www/ee6 ansel_ansel-php74 bash -c "XDEBUG_MODE=off composer install --no-interaction --no-ansi --no-progress";

    # Up the env
    docker compose -f docker-compose.yml -p ansel up -d;

    #
    # Set permissions on mounts
    #
    docker exec -w /var/www ansel-php74 bash -c "chmod 0755 ansel";
    docker exec -w /var/www ansel-php80 bash -c "chmod 0755 ansel";
    docker exec -w /var/www ansel-php81 bash -c "chmod 0755 ansel";

    docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee6/system/ee";
    docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee6/system/ee";
    docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee6/system/ee";

    docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee6/public/themes/ee";
    docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee6/public/themes/ee";
    docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee6/public/themes/ee";

    docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel";
    docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel";
    docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel";

    docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee6/public/themes/user/ansel";
    docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee6/public/themes/user/ansel";
    docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee6/public/themes/user/ansel";

    docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel/composer.json";
    docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel/composer.json";
    docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel/composer.json";

    docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel/composer.lock";
    docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel/composer.lock";
    docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel/composer.lock";

    docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel/config";
    docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel/config";
    docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel/config";

    docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel/src";
    docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel/src";
    docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel/src";

    docker exec -w /var/www ansel-php74 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel/vendor";
    docker exec -w /var/www ansel-php80 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel/vendor";
    docker exec -w /var/www ansel-php81 bash -c "chmod 0755 /var/www/ee6/system/user/addons/ansel/vendor";

    # Craft 3
    docker exec -w /var/www/craft3 ansel-php74 bash -c "composer install";
    docker exec -w /var/www/craft3/config ansel-php74 bash -c "chmod -R 0777 project";
    docker exec -w /var/www/craft3/public ansel-php74 bash -c "chmod -R 0777 cpresources";
    docker exec -w /var/www/craft3/public ansel-php74 bash -c "chmod -R 0777 uploads";
    docker exec -w /var/www/craft3 ansel-php74 bash -c "mkdir -p storage/session";
    docker exec -w /var/www/craft3 ansel-php74 bash -c "chmod -R 0777 storage";

    # EE 6
    docker exec -w /var/www/ee6/public ansel-php74 bash -c "chmod -R 0777 uploads";
    docker exec -w /var/www/ee6/system/user ansel-php74 bash -c "chmod -R 0777 cache";
    docker exec -w /var/www/ee6/system/user/config ansel-php74 bash -c "chmod 0666 config.php";

    cd ${START_DIR};

    return 0;
}
