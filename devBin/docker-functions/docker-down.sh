#!/usr/bin/env bash

function docker-down() {
    START_DIR=$(pwd);
    cd ${START_DIR}/docker;
    docker compose -f docker-compose.yml -p ansel down;
    return 0;
}
