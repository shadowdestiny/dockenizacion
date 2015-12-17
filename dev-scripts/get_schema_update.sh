#!/usr/bin/env bash
cd /var/www
php vendor/doctrine/orm/bin/doctrine orm:schema-tool:update --dump-sql
