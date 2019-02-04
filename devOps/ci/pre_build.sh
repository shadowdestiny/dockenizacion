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