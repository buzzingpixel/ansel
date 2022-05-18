#!/usr/bin/env bash

source ../../dev 2> /dev/null;

function create-databases() {
    docker exec -it treasuryee-db bash -c "chmod +x /opt/project/dev_envs/create-databases.sh && /opt/project/dev_envs/create-databases.sh";

    return 0;
}
