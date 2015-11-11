FROM antonienko/phalcon2-apache2:v1.0
MAINTAINER Antonio Hernández "antonio.hernandez@panamedia.net"

RUN crontab var/www/global_config/crontab.txt

RUN echo "INSTALLING LOCALES"

RUN locale-gen es_ES.UTF-8
RUN locale-gen en_US.UTF-8
RUN locale-gen en_GB.UTF-8