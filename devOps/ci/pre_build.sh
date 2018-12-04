#!/usr/bin/env bash

wget http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz
wget http://geolite.maxmind.com/download/geoip/database/GeoIPv6.dat.gz

gzip -fd GeoIP.dat.gz
gzip -fd GeoIPv6.dat.gz

mv GeoIP.dat ${WORKSPACE}/src/data/geoipdatabase
mv GeoIPv6.dat ${WORKSPACE}/src/data/geoipdatabase