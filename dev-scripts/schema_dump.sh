#!/usr/bin/env bash
cd /var/www
php vendor/doctrine/orm/bin/doctrine orm:schema-tool:create --dump-sql > tests/_data/dump.sql
