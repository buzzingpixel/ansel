#!/bin/sh

## Ansel Craft
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"CREATE DATABASE IF NOT EXISTS anselcraft\"";
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"CREATE USER IF NOT EXISTS 'anselcraft'@'%' IDENTIFIED BY 'secret'\"";
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"GRANT ALL ON anselcraft.* TO 'anselcraft'@'%'\"";

## Ansel EE 6
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"CREATE DATABASE IF NOT EXISTS anselee\"";
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"CREATE USER IF NOT EXISTS 'anselee'@'%' IDENTIFIED BY 'secret'\"";
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"GRANT ALL ON anselee.* TO 'anselee'@'%'\"";

## Ansel EE 7
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"CREATE DATABASE IF NOT EXISTS anselee7\"";
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"CREATE USER IF NOT EXISTS 'anselee7'@'%' IDENTIFIED BY 'secret'\"";
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"GRANT ALL ON anselee7.* TO 'anselee7'@'%'\"";

docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"FLUSH PRIVILEGES;\"";
