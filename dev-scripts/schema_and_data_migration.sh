#!/usr/bin/env bash
cd /var/www
vendor/bin/phinx migrate --configuration="phinx.yml" -e $1
vendor/bin/phinx seed:run --configuration="phinx.yml" -e $1
