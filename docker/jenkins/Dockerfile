FROM iliyan/jenkins-ci-php

MAINTAINER Antonio Hernández "antonio.hernandez@panameida.net"

RUN if [ ! -f /usr/local/bin/composer ]; then curl -s https://getcomposer.org/installer | php -- --install-dir=bin --filename=composer; fi
RUN pear channel-discover pear.phing.info
RUN pear install [--alldeps] phing/phing

RUN apt-get install -y --force-yes -q php5-dev

#Instalando Phalcon
RUN mkdir -p /tmp/phalcon
WORKDIR /tmp/phalcon
RUN git clone http://github.com/phalcon/cphalcon
WORKDIR /tmp/phalcon/cphalcon
RUN git checkout 2.0.0
RUN cd ext;\
    ./install
RUN echo 'extension=phalcon.so' > /etc/php5/cli/conf.d/30-phalcon.ini
RUN cd