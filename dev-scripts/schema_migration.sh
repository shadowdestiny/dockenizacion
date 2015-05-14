#!/usr/bin/env bash
environment="$1"
echo "executing schema migration on $1"
if [ "$environment" == "dev" ]; then
    echo "1"
    cd /var/www
    now=$(date)
    echo "$now: Schema migration executed" >> /vagrant/dev-logs/migration.log
    phalcon migration run
elif [ "$environment" == "shippable" ]; then
    echo "migrating..."
    cd src/
    vendor/bin/phalcon.php migration run
fi
if [ "$environment" == "shippable" ]; then
    echo "2"
    cd ..
fi
echo "finish migration"