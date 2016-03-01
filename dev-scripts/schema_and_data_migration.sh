#!/usr/bin/env bash
cd /var/www
vendor/bin/phinx migrate --configuration="phinx_schema.yml" -e $1
vendor/bin/phinx migrate --configuration="phinx_data.yml" -e $1