#!/bin/bash

# copy all container environment to file for crontab reads it
env > /etc/environment

/usr/bin/supervisord -c /etc/supervisor/supervisord.conf