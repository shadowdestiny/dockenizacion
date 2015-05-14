#!/usr/bin/env bash
environment="$1"
if [ environment = "dev" ]; then
    cd /var/www
    now=$(date)
    echo "$now: Schema migration executed" >> /vagrant/dev-logs/migration.log
    phalcon migration run
elif [ environment = "shippable" ]; then
    cd src/
    vendor/bin/phalcon.php migration run
fi
if [ environment = "shippable" ]; then
    cd ..
fi