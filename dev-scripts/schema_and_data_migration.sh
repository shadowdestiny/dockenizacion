#!/usr/bin/env bash
cd /var/www
phalcon migration run
vendor/bin/phinx migrate
now=$(date)
echo "$now: Schema and data migration executed" >> /vagrant/dev-logs/migration.log
