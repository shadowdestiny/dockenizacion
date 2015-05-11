#!/usr/bin/env bash
sudo sh -c "echo 'export EM_ENV=vagrant' >> /etc/profile"
sudo locale-gen UTF-8

####
## INSTALLING PHP5-CLI
## Installing php5-cli on the vagrant machine for PhpStorm configurations
####
if [ ! -f /etc/php5/cli/conf.d/20-xdebug.ini ]; then
    sudo apt-get install -y software-properties-common
    sudo apt-add-repository -y ppa:phalcon/stable;\
    sudo apt-add-repository -y ppa:ondrej/php5-5.6
    sudo apt-get clean
    sudo apt-get update -q
    sudo apt-get install -y php5-cli php5-dev php5-xdebug php5-mysql
    sudo cp /vagrant/vagrant_config/20-xdebug.ini /etc/php5/cli/conf.d
fi

####
## INSTALLING MYSQL CLIENT
## So the integration tests can run properly
####
if [ ! -f /usr/bin/mysql ]; then
    sudo apt-get install -y mysql-client-5.6
fi

####
## INSTALLING COMPOSER
####
if [ ! -f /usr/local/bin/composer ]; then
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    cd /var/www
    composer install
fi

####
## INSTALLING PHALCON
####
if [ ! -f /etc/php5/cli/conf.d/30-phalcon.ini ] || [ ! -f /usr/lib/php5/20131226/phalcon.so ]; then
    mkdir -p /tmp/phalcon
    cd /tmp/phalcon
    git clone http://github.com/phalcon/cphalcon
    cd /tmp/phalcon/cphalcon
    git checkout 2.0.0
    cd ext
    ./install
    sudo cp /vagrant/vagrant_config/30-phalcon.ini /etc/php5/cli/conf.d
fi

####
## PHALCON DEVTOOLS
####
if [ ! -f /usr/local/bin/phalcon ]; then
    sudo ln -s /var/www/vendor/phalcon/devtools/phalcon.php /usr/local/bin/phalcon
    sudo chmod ugo+x /usr/local/bin/phalcon
fi

####
## DOCTRINE COMMAND LINE
####
if [ ! -f /usr/local/bin/doctrine ]; then
    sudo ln -s /var/www/vendor/bin/doctrine /usr/local/bin/doctrine
    sudo chmod ugo+x /usr/local/bin/doctrine
fi

####
## CHECKING DOCKER INSTALLATION
####
dockerv=$(docker -v 2>&1)
if [[ $dockerv != "Docker version 1.6.0"* ]]
then
  echo "DOCKER NOT FOUND, INSTALLING...";
  wget -qO- https://get.docker.com/ | sh
fi
docker login --email="antonio.hernandez@panamedia.net" --username="antonienko" --password="wHTqwSg7wVyV47d3"

####
## INSTALLING DOCKER-COMPOSE
####
sudo sh -c "curl -L https://github.com/docker/compose/releases/download/1.2.0/docker-compose-`uname -s`-`uname -m` > /usr/local/bin/docker-compose"
sudo chmod +x /usr/local/bin/docker-compose

if [ ! -d "/home/vagrant/mysqldata" ]; then
  mkdir /home/vagrant/mysqldata
fi