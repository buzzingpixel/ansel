#!/usr/bin/env bash

function copy-treasury-dev() {
    ORIGINAL_DIR=${PWD};

    WORK_DIR="$(cd "$(dirname "$0")" >/dev/null 2>&1 && pwd)";

    CONTAINER_DIR=$(dirname "${WORK_DIR}");

    TREASURY_DIR="${CONTAINER_DIR}/treasury-ee";

    if [ ! -d "${TREASURY_DIR}" ]; then
        printf "${Red}Treasury directory does not exist${Reset}\n";

        return 1;
    fi

    if [[ -d "${WORK_DIR}/devResources/treasury-ee" ]]; then
        rm -rf ${WORK_DIR}/devResources/treasury-ee;
    fi

    cp -R ${TREASURY_DIR} ${WORK_DIR}/devResources;

    return 0;
}
