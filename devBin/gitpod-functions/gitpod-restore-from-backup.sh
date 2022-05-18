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

    docker-down;

    # Restore craft uploads
    rm -rf /workspace/ansel/docker/environments/craft3/public/uploads;
    cp /workspace/backup/craft-uploads.zip /workspace/ansel/docker/environments/craft3/public/craft-uploads.zip;
    cd /workspace/ansel/docker/environments/craft3/public;
    unzip craft-uploads.zip;
    rm craft-uploads.zip;

    # Restore ee user themes
    rm -rf /workspace/ansel/docker/environments/ee6/public/themes/user;
    cp /workspace/backup/ee-themes-user.zip /workspace/ansel/docker/environments/ee6/public/themes/ee-themes-user.zip;
    cd /workspace/ansel/docker/environments/ee6/public/themes;
    unzip ee-themes-user.zip;
    rm ee-themes-user.zip;

    # Restore ee uploads
    rm -rf /workspace/ansel/docker/environments/ee6/public/uploads;
    cp /workspace/backup/ee-uploads.zip /workspace/ansel/docker/environments/ee6/public/ee-uploads.zip;
    cd /workspace/ansel/docker/environments/ee6/public;
    unzip ee-uploads.zip;
    rm ee-uploads.zip;

    # Restore ee system user directory
    rm -rf /workspace/ansel/docker/environments/ee6/system/user;
    cp /workspace/backup/ee-system-user.zip /workspace/ansel/docker/environments/ee6/system/ee-system-user.zip;
    cd /workspace/ansel/docker/environments/ee6/system;
    unzip ee-system-user.zip;
    rm ee-system-user.zip;

    cd /workspace/ansel;

    docker-up;

    # Restore the craft database
    docker cp /workspace/backup/db/anselcraft.sql ansel-db:/anselcraft.sql;
    docker exec ansel-db bash -c "mysql -uanselcraft -psecret anselcraft < /anselcraft.sql";
    docker exec ansel-db bash -c "rm /anselcraft.sql";

    # Restore the ee database
    docker cp /workspace/backup/db/anselee.sql ansel-db:/anselee.sql;
    docker exec ansel-db bash -c "mysql -uanselee -psecret anselee < /anselee.sql";
    docker exec ansel-db bash -c "rm /anselee.sql";

    cd /workspace/ansel;

    return 0;
}
