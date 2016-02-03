#!/usr/bin/env bash
environment="$1"
echo "executing schema migration on $1"
if [ "$environment" == "devel" ]; then
    cd /var/www
    now=$(date)
    echo "$now: Schema migration executed" >> /vagrant/dev-logs/migration.log
    vendor/bin/phinx migrate --configuration="phinx_schema.yml" -e devel
elif [ "$environment" == "shippable" ]; then
    echo "migrating..."
    cd src/
    vendor/bin/phinx migrate --configuration="phinx_schema.yml" -e shippable
fi
if [ "$environment" == "shippable" ]; then
    cd ..
fi
echo "finish migration"