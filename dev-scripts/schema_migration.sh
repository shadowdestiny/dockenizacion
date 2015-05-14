#!/usr/bin/env bash
environment="$1"
echo "executing schema migration on $1"
if [ "$environment" == "dev" ]; then
    cd /var/www
    now=$(date)
    echo "$now: Schema migration executed" >> /vagrant/dev-logs/migration.log
    phalcon migration run
elif [ "$environment" == "shippable" ]; then
    echo "migrating..."
    cd src/pjh
    vendor/bin/phalcon.php migration run
fi
if [ "$environment" == "shippable" ]; then
    cd ..
fi
echo "finish migration"