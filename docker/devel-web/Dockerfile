#Developer web machine v0.0.1
FROM antonienko/phalcon2-apache2

MAINTAINER Antonio Hernández "antonio.hernandez@panamedia.net"

ADD crontab.txt /tmp/crontab.txt
RUN crontab /tmp/crontab.txt

RUN apt-get install -y wget
RUN wget -O - https://download.newrelic.com/548C16BF.gpg | apt-key add -
RUN sh -c 'echo "deb http://apt.newrelic.com/debian/ newrelic non-free" > /etc/apt/sources.list.d/newrelic.list'
RUN apt-get update
RUN apt-get install -y newrelic-php5
RUN newrelic-install install
RUN echo 'newrelic.license="52fbca180ae7607033f787d977a44971ae7a0876"' > /etc/php5/apache2/conf.d/newrelic.ini
RUN echo 'newrelic.license="52fbca180ae7607033f787d977a44971ae7a0876"' > /etc/php5/cli/conf.d/newrelic.ini