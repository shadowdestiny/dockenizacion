#!/usr/bin/env bash
cd /var/www
vendor/bin/phinx seed:run --configuration="phinx_data.yml" -e $1
