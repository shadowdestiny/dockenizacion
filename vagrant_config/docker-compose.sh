#!/usr/bin/env bash
function e {
    echo "####################################"
    echo $1
    echo "####################################"
}
cd /vagrant/docker
e "Starting DB"
docker-compose up -d
e "Sleeping"
sleep 30
e "Initializing database"
mysql -h 127.0.0.1 -u root -ptpl9 euromillions < /vagrant/docker/devel-dbmaster/dbinit/init_structure.sql
cat /vagrant/docker/devel-dbmaster/dbinit/data/devel/*.sql | mysql -h 127.0.0.1 -u root -ptpl9 euromillions --default-character-set=utf8
e "Executing migrations"
. /vagrant/dev-scripts/schema_and_data_migration.sh dev

