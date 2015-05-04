#!/usr/bin/env bash
echo "DATABASE ENTRYPOING"
. /docker-entrypoint.sh mysqld
sleep 30
echo "INITIALIZING DATABASE"
mysql -u root -ptpl9 euromillions < /dbinit/init_structure.sql
echo "INITIALIZING DATA"
cat /dbinit/data/*.sql | mysql -u root -ptpl9 euromillions --default-character-set=utf8
