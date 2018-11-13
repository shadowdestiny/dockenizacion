#!/usr/bin/env bash

cd /var/www/grunt \
    && npm install grunt-cli \
    && npm install grunt --save-dev \
    && npm install \
    && ./node_modules/grunt/bin/grunt