#!/usr/bin/env bash

function container-node-help() {
    printf "[some_command] (Execute command in \`node\` container. Empty argument starts a bash session)";
}

function container-node() {
    if [ -t 0 ]; then
        interactiveArgs='-it';
    else
        interactiveArgs='';
    fi

    printf "${Yellow}You're working inside the 'node' container of this project.${Reset}\n";

    if [[ -z "${allArgsExceptFirst}" ]]; then
        printf "${Yellow}Remember to 'exit' when you're done.${Reset}\n";
        docker run --rm ${interactiveArgs} \
            --name ansel-node \
            -v ${PWD}:/app \
            -w /app \
            ansel-node sh;
    else
        docker run --rm ${interactiveArgs} \
            --name ansel-node \
            -v ${PWD}:/app \
            -w /app \
            ansel-node sh -c "${allArgsExceptFirst}";
    fi

    return 0;
}
