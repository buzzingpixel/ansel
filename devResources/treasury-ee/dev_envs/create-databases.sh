#!/usr/bin/env bash

# treasuryee3
mysql -uroot -proot -e "CREATE DATABASE treasuryee3";
mysql -uroot -proot -e "CREATE USER 'treasuryee3'@'%' IDENTIFIED BY 'secret'";
mysql -uroot -proot -e "GRANT ALL on treasuryee3.* to 'treasuryee3'@'%'";

# treasuryee4
mysql -uroot -proot -e "CREATE DATABASE treasuryee4";
mysql -uroot -proot -e "CREATE USER 'treasuryee4'@'%' IDENTIFIED BY 'secret'";
mysql -uroot -proot -e "GRANT ALL on treasuryee4.* to 'treasuryee4'@'%'";

# treasuryee5
mysql -uroot -proot -e "CREATE DATABASE treasuryee5";
mysql -uroot -proot -e "CREATE USER 'treasuryee5'@'%' IDENTIFIED BY 'secret'";
mysql -uroot -proot -e "GRANT ALL on treasuryee5.* to 'treasuryee5'@'%'";

# treasuryee6
mysql -uroot -proot -e "CREATE DATABASE treasuryee6";
mysql -uroot -proot -e "CREATE USER 'treasuryee6'@'%' IDENTIFIED BY 'secret'";
mysql -uroot -proot -e "GRANT ALL on treasuryee6.* to 'treasuryee6'@'%'";
