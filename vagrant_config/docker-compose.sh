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
e "Sleeping"
sleep 30
if [ inidatabase==true ]; then
    e "Initializing database"
    mysql -h 127.0.0.1 -u root -ptpl9 -e 'CREATE DATABASE IF NOT EXISTS euromillions_test'
fi
e "Executing migrations"
. /vagrant/dev-scripts/schema_and_data_migration.sh dev

e "Updating jackpot and results"
php /var/www/app/cli.php jackpot updatePrevious
php /var/www/app/cli.php result update
php /var/www/app/cli.php jackpot update
