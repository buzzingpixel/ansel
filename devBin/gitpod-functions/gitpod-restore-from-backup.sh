#!/usr/bin/env bash

function gitpod-restore-from-backup() {
    FILE_EXISTS=false;

    if [[ -f "/workspace/backup/craft-uploads.zip" ]]; then
        FILE_EXISTS=true;
    fi

    if [[ "${FILE_EXISTS}" = "false" ]]; then
        echo 'Backup does not exist';

        return 1;
    fi

    # Restore the craft database
    docker cp /workspace/backup/db/anselcraft.sql ansel-db:/anselcraft.sql;
    docker exec ansel-db bash -c "mysql -uanselcraft -psecret anselcraft < /anselcraft.sql";
    docker exec ansel-db bash -c "rm /anselcraft.sql";

    # Backup craft uploads
    rm -rf /workspace/ansel/docker/environments/craft3/public/uploads;
    cp /workspace/backup/craft-uploads.zip /workspace/ansel/docker/environments/craft3/public/craft-uploads.zip;
    cd /workspace/ansel/docker/environments/craft3/public;
    unzip craft-uploads.zip;
    rm craft-uploads.zip;

    return 0;
}
