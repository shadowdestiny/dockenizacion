#!/usr/bin/env bash
cd /vagrant/docker
echo "STARTING DB"
docker-compose up -d
echo "SLEEPING"
sleep 30
echo "INITIALIZING DATABASE"
mysql -h 127.0.0.1 -u root -ptpl9 euromillions < /vagrant/docker/devel-dbmaster/dbinit/init_structure.sql
cat /vagrant/docker/devel-dbmaster/dbinit/data/devel/*.sql | mysql -h 127.0.0.1 -u root -ptpl9 euromillions --default-character-set=utf8

