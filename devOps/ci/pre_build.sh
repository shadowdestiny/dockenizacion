#!/usr/bin/env bash

# GeoIp Database
wget https://geolite.maxmind.com/download/geoip/database/GeoLite2-Country.tar.gz \
&& tar -zxvf GeoLite2-Country.tar.gz \
&& mv GeoLite2-Country_*/GeoLite2-Country.mmdb ${WORKSPACE}/src/data/geoipdatabase/GeoLite2-Country.mmdb \
&& rm -rf GeoLite2-Country.tar.gz GeoLite2-Country_*

# Build php tool image
sudo docker build --target tools -f ${WORKSPACE}/devOps/docker/php/Dockerfile -t panamedialottery/euromillions-php:tools ${WORKSPACE}

# Run Ansible Playbook
ansible-playbook --vault-password-file ${ANSIBLE_VAULT_PASSWORD_FILE} ${WORKSPACE}/devOps/playbook_jenkins.yml