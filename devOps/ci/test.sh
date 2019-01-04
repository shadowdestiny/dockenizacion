#!/usr/bin/env bash

sudo docker login --username $DOCKER_HUB_USER --password $DOCKER_HUB_PASSWORD

sudo docker pull panamedialottery/euromillions-php:tools

sudo docker build --target tools -f ${WORKSPACE}/devOps/docker/php/Dockerfile -t panamedialottery/euromillions-php:tools ${WORKSPACE}

sudo docker push panamedialottery/euromillions-php:tools

sudo docker run --rm -v ${WORKSPACE}/src:/var/www panamedialottery/euromillions-php:tools composer install --no-progress

sudo docker-compose -p test -f ${WORKSPACE}/docker-compose.test.yml build
sudo docker-compose -p test -f ${WORKSPACE}/docker-compose.test.yml up -d
sudo docker-compose -p test -f ${WORKSPACE}/docker-compose.test.yml exec -T php ./vendor/bin/phpunit --log-junit tests/junit.xml -c tests/phpunit.xml