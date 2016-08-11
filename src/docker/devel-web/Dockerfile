FROM antonienko/nginx-php-fpm:v0.4
MAINTAINER Antonio Hernández "antonio.hernandez@panamedia.net"

RUN echo "INSTALLING LOCALES"

RUN locale-gen es_ES.UTF-8
RUN locale-gen en_US.UTF-8
RUN locale-gen en_GB.UTF-8

ADD ./30-phalcon.ini /etc/php5/mods-available
RUN php5enmod 30-phalcon