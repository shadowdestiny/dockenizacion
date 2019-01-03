#!/bin/bash

# Launch doctrine proxies and fix permissions
cd /var/www && php vendor/doctrine/orm/bin/doctrine orm:generate-proxies;
chown www-data:www-data /tmp/__CG__*;