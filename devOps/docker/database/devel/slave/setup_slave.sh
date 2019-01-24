#!/usr/bin/env bash

until export MYSQL_PWD=tpl9_slave; mysql -u root -e ";"
do
    echo "Waiting for SLAVE database connection..."
    sleep 4
done

# Connect to the master for get the MASTER STATUS
MS_STATUS=`export MYSQL_PWD=tpl9; mysql -h master -u root -e "SHOW MASTER STATUS"`
CURRENT_LOG=`echo $MS_STATUS | awk '{print $6}'`
CURRENT_POS=`echo $MS_STATUS | awk '{print $7}'`

# Setup and Start the SLAVE
export MYSQL_PWD=tpl9_slave; mysql -u root -e "CHANGE MASTER TO MASTER_HOST='master',MASTER_USER='root',MASTER_PASSWORD='tpl9',MASTER_LOG_FILE='$CURRENT_LOG',MASTER_LOG_POS=$CURRENT_POS; START SLAVE;"
export MYSQL_PWD=tpl9_slave; mysql -u root -e "SHOW SLAVE STATUS \G"

