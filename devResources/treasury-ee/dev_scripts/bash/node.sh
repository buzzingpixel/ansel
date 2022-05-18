#!/usr/bin/env bash

source ../../dev 2> /dev/null;

function node() {
    docker run -it \
        -p 3000:3000 \
        -p 3001:3001 \
        -v ${PWD}:/app \
        -v treasuryee_node-modules-volume:/app/node_modules \
        -v treasuryee_yarn-cache-volume:/usr/local/share/.cache/yarn \
        -w /app \
        --network=proxy \
        treasuryee_node \
        bash;

    return 0;
}
