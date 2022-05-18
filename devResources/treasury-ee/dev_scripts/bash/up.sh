#!/usr/bin/env bash

source ../../dev 2> /dev/null;

function up() {
    docker network create proxy >/dev/null 2>&1

    COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker-compose ${composeFiles} -p treasuryee up -d;

    docker exec -it treasuryee-php73 bash -c "chmod -R 0777 /opt/project/dev_envs/ee3/system/user/cache";
    docker exec -it treasuryee-php73 bash -c "chmod -R 0666 /opt/project/dev_envs/ee3/system/user/config/config.php";
    docker exec -it treasuryee-php73 bash -c "chmod -R 0777 /opt/project/dev_envs/ee3/system/user/templates";
    docker exec -it treasuryee-php73 bash -c "chmod -R 0777 /opt/project/dev_envs/ee3/public/themes/user";
    docker exec -it treasuryee-php73 bash -c "chmod -R 0777 /opt/project/dev_envs/ee3/public/uploads";

    docker exec -it treasuryee-php73 bash -c "chmod -R 0777 /opt/project/dev_envs/ee4/system/user/cache";
    docker exec -it treasuryee-php73 bash -c "chmod -R 0666 /opt/project/dev_envs/ee4/system/user/config/config.php";
    docker exec -it treasuryee-php73 bash -c "chmod -R 0777 /opt/project/dev_envs/ee4/public/themes/user";
    docker exec -it treasuryee-php73 bash -c "chmod -R 0777 /opt/project/dev_envs/ee4/public/uploads";

    docker exec -it treasuryee-php73 bash -c "chmod -R 0777 /opt/project/dev_envs/ee5/system/user/cache";
    docker exec -it treasuryee-php73 bash -c "chmod -R 0666 /opt/project/dev_envs/ee5/system/user/config/config.php";
    docker exec -it treasuryee-php73 bash -c "chmod -R 0777 /opt/project/dev_envs/ee5/public/uploads";

    docker exec -it treasuryee-php73 bash -c "chmod -R 0777 /opt/project/dev_envs/ee6/system/user/cache";
    docker exec -it treasuryee-php73 bash -c "chmod -R 0666 /opt/project/dev_envs/ee6/system/user/config/config.php";
    docker exec -it treasuryee-php73 bash -c "chmod -R 0777 /opt/project/dev_envs/ee6/public/uploads";

    return 0;
}
