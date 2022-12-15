#!/bin/sh

## Ansel Craft 3
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"CREATE DATABASE IF NOT EXISTS anselcraft\"";
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"CREATE USER IF NOT EXISTS 'anselcraft'@'%' IDENTIFIED BY 'secret'\"";
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"GRANT ALL ON anselcraft.* TO 'anselcraft'@'%'\"";

## Ansel EE 7
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"CREATE DATABASE IF NOT EXISTS anselee7\"";
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"CREATE USER IF NOT EXISTS 'anselee7'@'%' IDENTIFIED BY 'secret'\"";
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"GRANT ALL ON anselee7.* TO 'anselee7'@'%'\"";

## Ansel Coilpack
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"CREATE DATABASE IF NOT EXISTS coilpack\"";
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"CREATE USER IF NOT EXISTS 'coilpack'@'%' IDENTIFIED BY 'secret'\"";
docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"GRANT ALL ON coilpack.* TO 'coilpack'@'%'\"";

docker exec -it ansel-db bash -c "mysql -uroot -proot -e \"FLUSH PRIVILEGES;\"";
