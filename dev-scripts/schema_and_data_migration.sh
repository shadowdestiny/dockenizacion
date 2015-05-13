#!/usr/bin/env bash
cd /var/www
phalcon migration run
vendor/bin/phinx migrate
now=$(date)
touch "migration_executed_$now"
