FROM iliyan/jenkins-ci-php

MAINTAINER Antonio Hernández "antonio.hernandez@panameida.net"

RUN if [ ! -f /usr/local/bin/composer ]; then curl -s https://getcomposer.org/installer | php -- --install-dir=bin --filename=composer; fi
