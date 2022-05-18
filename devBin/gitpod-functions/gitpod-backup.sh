#!/usr/bin/env bash

function gitpod-backup() {
    # Create directories
    mkdir -p /workspace/backup;
    rm -rf /workspace/backup
    mkdir -p /workspace/backup;
    mkdir -p /workspace/backup/db;

    docker-down;
    docker-up;

    # Backup the craft database
    docker exec ansel-db bash -c "mysqldump -uanselcraft -psecret anselcraft > /anselcraft.sql";
    docker cp ansel-db:/anselcraft.sql /workspace/backup/db/anselcraft.sql;
    docker exec ansel-db bash -c "rm /anselcraft.sql";

    # Backup the ee database
    docker exec ansel-db bash -c "mysqldump -uanselee -psecret anselee > /anselee.sql";
    docker cp ansel-db:/anselee.sql /workspace/backup/db/anselee.sql;
    docker exec ansel-db bash -c "rm /anselee.sql";

    # Backup craft uploads
    cd /workspace/ansel/docker/environments/craft3/public;
    zip -r /workspace/backup/craft-uploads.zip uploads;

    # Backup ee user themes
    cd /workspace/ansel/docker/environments/ee6/public/themes;
    zip -r /workspace/backup/ee-themes-user.zip user;

    # Backup ee uploads
    cd /workspace/ansel/docker/environments/ee6/public;
    zip -r /workspace/backup/ee-uploads.zip uploads;

    # Backup ee system user directory
    cd /workspace/ansel/docker/environments/ee6/system;
    zip -r /workspace/backup/ee-system-user.zip user;

    return 0;
}
