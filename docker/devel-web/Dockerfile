# Developer www machine v0.0.1
FROM ubuntu:14.04

MAINTAINER Antonio Hernández "antonio.hernandez@panamedia.net"

#Configurando repositorios y actualizando distribución
RUN apt-get install -y software-properties-common;\
    apt-add-repository -y ppa:phalcon/stable;\
    apt-get update -q;\
    apt-get upgrade -y -q;\
    apt-get dist-upgrade -y -q

#Instalando Git, Php5, Apache2 y Phalcon
RUN apt-get install -y -q php5-mcrypt php5-curl php5-mysql php5-sqlite php5-memcached php5-xdebug php-apc git php5-phalcon apache2 libapache2-mod-php5

#Habilitando mod_rewrite
RUN a2enmod rewrite

#Modificando la configuración de apache
ADD apache-config /etc/apache2/sites-available/000-default.conf

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_RUN_DIR /var/run/apache2
ENV APACHE_PID_FILE /var/run/apache2/apache2.pid

RUN echo 'Hi, I am in your container' > /var/www/index.html
RUN echo '<?php phpinfo();?>' > /var/www/phpinfo.php

EXPOSE 80

ENTRYPOINT ["/usr/sbin/apache2"]
CMD ["-D", "FOREGROUND"]