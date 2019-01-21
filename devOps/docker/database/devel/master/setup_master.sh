#!/usr/bin/env bash

until export MYSQL_PWD=tpl9; mysql -u root -e ";"
do
    echo "Waiting for MASTER database connection..."
    sleep 4
done

# Enable REPLICATION SLAVE
export MYSQL_PWD=tpl9; mysql -u root -e "GRANT REPLICATION SLAVE ON *.* TO 'root'@'%' IDENTIFIED BY 'tpl9'; FLUSH PRIVILEGES;"