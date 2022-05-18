#!/usr/bin/env bash

function docker-up() {
    START_DIR=$(pwd);
    cd ${START_DIR}/docker;
    docker compose -f docker-compose.yml -p ansel up -d;
    docker exec -w /var/www ansel-php74 bash -c "XDEBUG_MODE=off cd /var/www/craft3 && composer install";
    docker exec -w /var/www ansel-php74 bash -c "XDEBUG_MODE=off cd /var/www/craft3/config && chmod -R 0777 project";
    docker exec -w /var/www ansel-php74 bash -c "XDEBUG_MODE=off cd /var/www/craft3/public && chmod -R 0777 cpresources";
    docker exec -w /var/www ansel-php74 bash -c "XDEBUG_MODE=off cd /var/www/craft3/public && chmod -R 0777 uploads";
    docker exec -w /var/www ansel-php74 bash -c "XDEBUG_MODE=off cd /var/www/craft3 && chmod -R 0777 storage";
    docker exec -w /var/www ansel-php74 bash -c "XDEBUG_MODE=off chmod 0755 ansel";
    return 0;
}
