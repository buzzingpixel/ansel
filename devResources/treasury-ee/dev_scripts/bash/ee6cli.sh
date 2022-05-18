#!/usr/bin/env bash

source ../../dev 2> /dev/null;

function ee6cli() {
    docker exec -it treasuryee-php73 bash -c "php /opt/project/dev_envs/ee6/eecli.php";

    return 0;
}
