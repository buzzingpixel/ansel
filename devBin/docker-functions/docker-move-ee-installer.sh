#!/usr/bin/env bash

function docker-move-ee-installer() {
    START_DIR=$(pwd);

    cd ${START_DIR}/docker/environments/ee6/vendor/expressionengine/expressionengine/system/ee;

    sudo mv installer installer_back;

    return 0;
}
