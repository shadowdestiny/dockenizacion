#!/usr/bin/env bash
cd /var/www
vendor/bin/phinx seed:run --configuration="phinx.yml" -e $1
