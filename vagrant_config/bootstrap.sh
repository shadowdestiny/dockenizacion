#!/usr/bin/env bash
function e {
    echo "####################################"
    echo $1
    echo "####################################"
}

####
## START
####
e "Exporting environment and generating locale"
sudo sh -c "echo 'export EM_ENV=vagrant' >> /etc/profile"
sudo locale-gen UTF-8

####
## PRIVILEGES TO THE DEV-SCRIPTS
####
e "Adding privileges to the devel scripts"
chmod 755 /vagrant/dev-scripts/*

####
## INSTALLING PHP5-CLI
## Installing php5-cli on the vagrant machine for PhpStorm configurations
####
e "Checking PHP5 Installation"
if [ ! -f /etc/php5/cli/conf.d/20-xdebug.ini ]; then
    e "Installing PHP"
    sudo apt-get install -y software-properties-common
    sudo apt-add-repository -y ppa:phalcon/stable;\
    sudo apt-add-repository -y ppa:ondrej/php5-5.6
    sudo apt-get clean
    sudo apt-get update -q
    sudo apt-get install -y php5-cli php5-dev php5-xdebug php5-mysql
    sudo cp /vagrant/vagrant_config/20-xdebug.ini /etc/php5/cli/conf.d
fi

e "Installing git"
sudo apt-get install -y git

####
## INSTALLING MYSQL CLIENT
## So the integration tests can run properly
####
e "Checking Mysql Client Installation"
if [ ! -f /usr/bin/mysql ]; then
    e "Installing mysql client"
    sudo apt-get install -y mysql-client-5.6
fi

####
## INSTALLING COMPOSER
####
e "Checking composer installation"
if [ ! -f /usr/local/bin/composer ]; then
    e "installing composer and installing libraries"
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    cd /var/www
    composer install
fi

####
## INSTALLING PHALCON
####
e "Checking Phalcon installation"
if [ ! -f /etc/php5/cli/conf.d/30-phalcon.ini ] || [ ! -f /usr/lib/php5/20131226/phalcon.so ]; then
    e "Installing Phalcon"
    mkdir -p /tmp/phalcon
    cd /tmp/phalcon
    git clone http://github.com/phalcon/cphalcon
    cd /tmp/phalcon/cphalcon/build
    sudo ./install
    sudo cp /vagrant/vagrant_config/30-phalcon.ini /etc/php5/cli/conf.d
fi

####
## PHALCON DEVTOOLS
####
e "Creating softlink for phalcon dev-tools"
if [ ! -f /usr/local/bin/phalcon ]; then
    sudo ln -s /var/www/vendor/phalcon/devtools/phalcon.php /usr/local/bin/phalcon
    sudo chmod ugo+x /usr/local/bin/phalcon
fi

####
## DOCTRINE COMMAND LINE
####
e "Creating softlink for doctrine command line utilities"
if [ ! -f /usr/local/bin/doctrine ]; then
    sudo ln -s /var/www/vendor/bin/doctrine /usr/local/bin/doctrine
    sudo chmod ugo+x /usr/local/bin/doctrine
fi

####
## CHECKING DOCKER INSTALLATION
####
e "Checking docker installation"
dockerv=$(docker -v 2>&1)
if [[ $dockerv != "Docker version"* ]]
then
  e "Intalling docker";
  wget -qO- https://get.docker.com/ | sh
fi
sudo docker login --email="antonio.hernandez@panamedia.net" --username="antonienko" --password="wHTqwSg7wVyV47d3"

####
## INSTALLING DOCKER-COMPOSE
####
e "Checking docker compose installation"
dockercomposev=$(docker-compose --version 2>&1)
if [[ $dockercomposev != "docker-compose"* ]]
then
    e "Installing docker compose"
    sudo sh -c "curl -L https://github.com/docker/compose/releases/download/1.2.0/docker-compose-`uname -s`-`uname -m` > /usr/local/bin/docker-compose"
    sudo chmod +x /usr/local/bin/docker-compose
fi

e "Checking mysql data directory"
if [ ! -d "/home/vagrant/mysqldata" ]; then
  mkdir /home/vagrant/mysqldata
fi