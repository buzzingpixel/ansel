#!/usr/bin/env bash

function docker-up() {
    START_DIR=$(pwd);
    cd ${START_DIR}/docker;
    docker compose -f docker-compose.yml -p ansel up -d;
    return 0;
}
