#!/usr/bin/env bash

if [ -d /var/www/react/node_modules ]; then
    rm -r /var/www/react/node_modules
fi

cd /var/www/react \
    && npm cache clean --force \
    && npm install --save-dev \
    && npm run build