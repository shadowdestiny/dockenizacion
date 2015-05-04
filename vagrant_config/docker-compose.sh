#!/usr/bin/env bash
cd /vagrant/docker
echo "STARTING DB"
docker-compose up -d --no-recreate develdbmaster
echo "SLEEPING"
sleep 30
echo "STARTING RESTING CONTAINERS"
docker-compose up -d --no-recreate
