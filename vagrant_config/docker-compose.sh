#!/usr/bin/env bash
function e {
    echo "####################################"
    echo $1
    echo "####################################"
}
e "Checking if database exists"
if [ ! -d "/home/vagrant/mysqldata/euromillions" ]; then
    inidatabase=true
else
    inidatabase=false
fi
cd /vagrant/docker
e "Starting containers"
docker-compose up -d
e "Installing cron"
docker exec docker_develweb_1 crontab /var/www/global_config/crontab.txt
e "Sleeping"
sleep 30
if [ inidatabase==true ]; then
    e "Initializing database"
    mysql -h 127.0.0.1 -u root -ptpl9 -e 'CREATE DATABASE IF NOT EXISTS euromillions_test'
fi

