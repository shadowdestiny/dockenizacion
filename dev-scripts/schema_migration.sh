#!/usr/bin/env bash
environment="$1"
if [ environment = "dev" ]; then
    cd /var/www
    now=$(date)
    echo "$now: Schema migration executed" >> /vagrant/dev-logs/migration.log
elif [ environment = "shippable" ]; then
    cd src/
fi
phalcon migration run
if [ environment = "shippable" ]; then
    cd ..
fi