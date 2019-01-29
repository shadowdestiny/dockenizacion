#!/usr/bin/env bash

#wget http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz
wget https://s3-eu-west-1.amazonaws.com/geoip-euromillions-hotfix/GeoIP.dat.gz

#wget http://geolite.maxmind.com/download/geoip/database/GeoIPv6.dat.gz
wget https://s3-eu-west-1.amazonaws.com/geoip-euromillions-hotfix/GeoIPv6.dat.gz

gzip -fd GeoIP.dat.gz
gzip -fd GeoIPv6.dat.gz

mv GeoIP.dat ${WORKSPACE}/src/data/geoipdatabase
mv GeoIPv6.dat ${WORKSPACE}/src/data/geoipdatabase

# Build php tool image
sudo docker build --target tools -f ${WORKSPACE}/devOps/docker/php/Dockerfile -t panamedialottery/euromillions-php:tools ${WORKSPACE}

# Temporal hack for deploy in both infrastructures ( old and new )
mv ${WORKSPACE}/src/phinx_new_infra.yml ${WORKSPACE}/src/phinx.yml
mv ${WORKSPACE}/src/apps/shared/config/staging_config_new_infra.ini ${WORKSPACE}/src/apps/shared/config/staging_config.ini
mv ${WORKSPACE}/src/apps/shared/config/production_config_new_infra.ini ${WORKSPACE}/src/apps/shared/config/production_config.ini

