#!/usr/bin/env bash

sudo docker run --rm -v ${WORKSPACE}/src:/var/www -v /tmp:/tmp -e "COMPOSER_HOME=/tmp/composer_home" panamedialottery/euromillions-php:tools composer install --no-progress

# Create .env file for setup environment. That file is created from .env.test.j2 via Ansible
cp ${WORKSPACE}/.env.test .env

sudo docker-compose -p ${TEST_PROJECT_NAME} -f ${WORKSPACE}/docker-compose.test.yml build
sudo docker-compose -p ${TEST_PROJECT_NAME} -f ${WORKSPACE}/docker-compose.test.yml up -d
sudo docker-compose -p ${TEST_PROJECT_NAME} -f ${WORKSPACE}/docker-compose.test.yml exec -T php ./vendor/bin/phpunit --log-junit tests/junit.xml -c tests/phpunit.xml