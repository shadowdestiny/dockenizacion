#!/bin/bash

# copy all container environment to file for crontab reads it
env > /etc/environment

# Run cron on foreground
cron -f