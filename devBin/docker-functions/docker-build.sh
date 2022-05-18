#!/usr/bin/env bash

function docker-build() {
    START_DIR=$(pwd);
    cd ${START_DIR}/docker;
    docker compose -f docker-compose.yml -p ansel build;
    return 0;
}
