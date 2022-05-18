#!/usr/bin/env bash

source ../../dev 2> /dev/null;

function down() {
    docker kill treasuryee-bg-sync-node-modules;
    docker-compose ${composeFiles} -p treasuryee down;

    return 0;
}
