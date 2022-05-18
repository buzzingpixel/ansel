#!/usr/bin/env bash

#!/usr/bin/env bash

function docker-create-databases-help() {
    printf "(Creates databases)";
}

function docker-create-databases() {
    # ExpressionEngine
    docker exec ansel-db bash -c "mysql -uroot -proot -e \"CREATE DATABASE anselee\"";
    docker exec ansel-db bash -c "mysql -uroot -proot -e \"CREATE USER 'anselee'@'%' IDENTIFIED BY 'secret'\"";
    docker exec ansel-db bash -c "mysql -uroot -proot -e \"GRANT ALL on anselee.* to 'anselee'@'%'\"";

    # Craft
    docker exec ansel-db bash -c "mysql -uroot -proot -e \"CREATE DATABASE anselcraft\"";
    docker exec ansel-db bash -c "mysql -uroot -proot -e \"CREATE USER 'anselcraft'@'%' IDENTIFIED BY 'secret'\"";
    docker exec ansel-db bash -c "mysql -uroot -proot -e \"GRANT ALL on anselcraft.* to 'anselcraft'@'%'\"";

    return 0;
}
