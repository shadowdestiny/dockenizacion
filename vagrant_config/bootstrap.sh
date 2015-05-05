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
else
    cd /var/www
    composer update --no-interaction
fi

####
## INSTALLING PHALCON
####
if [ ! -f /etc/php5/cli/conf.d/30-phalcon.ini ]; then
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

#cd /vagrant/docker/devel-dbmaster
#sudo docker build -t="panamedia/devel-dbmaster" .
#sudo docker stop devel-dbmaster
#sudo docker rm devel-dbmaster
#sudo docker run -v /home/vagrant/mysqldata:/var/lib/mysql/ --name devel-dbmaster -p 3306:3306 -e MYSQL_ROOT_PASSWORD=tpl9 -e MYSQL_DATABASE=euromillions -d panamedia/devel-dbmaster
#sudo docker exec -d devel-dbmaster /dbinit/init_database.sh

####
## BUILDING DEVEL-WEB CONTAINER
####
#cd /vagrant/docker/devel-web
#sudo docker build -t="panamedia/devel-web" .
#sudo docker stop devel-web
#sudo docker rm devel-web
#sudo docker run -v /var/www:/var/www/ -d -p 8080:80 --name devel-web --link devel-dbmaster:mysql panamedia/devel-web