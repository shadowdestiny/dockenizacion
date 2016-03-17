#!/usr/bin/env bash
environment="$1"
echo "executing schema migration on $1"
if [ "$environment" == "devel" ]; then
    cd /var/www
    vendor/bin/phinx migrate --configuration="phinx.yml" -e devel
elif [ "$environment" == "shippable" ] || [ "$environment" == "scrutinizer" ]; then
    cd src/
    vendor/bin/phinx migrate --configuration="phinx.yml" -e shippable
    cd ..
fi
echo "finish migration"