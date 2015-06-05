#!/usr/bin/env bash
# $1 = migration name
cd /var/www
vendor/bin/phinx create --configuration="phinx_data.yml" $1
