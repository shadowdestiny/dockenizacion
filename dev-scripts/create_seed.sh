#!/usr/bin/env bash
# $1 = seed name
cd /var/www
vendor/bin/phinx seed:create --configuration="phinx.yml" $1
