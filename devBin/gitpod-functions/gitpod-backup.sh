#!/usr/bin/env bash

function gitpod-backup() {
    # Create directories
    mkdir -p /workspace/backup;
    rm -rf /workspace/backup
    mkdir -p /workspace/backup;
    mkdir -p /workspace/backup/db;

    # Backup the craft database
    docker exec ansel-db bash -c "mysqldump -uanselcraft -psecret anselcraft > /anselcraft.sql";
    docker cp ansel-db:/anselcraft.sql /workspace/backup/db/anselcraft.sql;
    docker exec ansel-db bash -c "rm /anselcraft.sql";

    # Backup craft uploads
    cd /workspace/ansel/docker/environments/craft3/public;
    zip -r /workspace/backup/craft-uploads.zip uploads;

    return 0;
}
