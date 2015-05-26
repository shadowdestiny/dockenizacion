#!/usr/bin/env bash
cd /var/www
doctrine orm:schema-tool:update --dump-sql