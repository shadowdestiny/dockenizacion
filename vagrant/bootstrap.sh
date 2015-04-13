#!/usr/bin/env bash
####
## INSTALLING PHP5-CLI
## Installing php5-cli on the vagrant machine for PhpStorm configurations
####
if [ ! -f /etc/php5/cli/conf.d/20-xdebug.ini ]; then
    sudo apt-get install -y software-properties-common
    sudo apt-add-repository -y ppa:ondrej/php5-5.6
    sudo apt-get clean
    sudo apt-get update -q
    sudo apt-get install -y php5-cli php5-xdebug
    sudo cp /vagrant/20-xdebug.ini /etc/php5/cli/conf.d
fi

####
## CHECKING DOCKER INSTALLATION
####
dockerv=$(docker -v 2>&1)
if [[ $dockerv != "Docker version 1.5.0"* ]]
then
  echo "DOCKER NOT FOUND, INSTALLING...";
  wget -qO- https://get.docker.com/ | sh
fi
docker login --email="antonio.hernandez@panamedia.net" --username="panamedia" --password="2Fsz8EGmy6LhKCR5"

####
## BUILDING DEVEL-DBMASTER CONTAINER
####
cd /docker/devel-dbmaster
sudo docker build -t="panamedia/devel-dbmaster" .
sudo docker stop devel-dbmaster
sudo docker rm devel-dbmaster
sudo docker run --name devel-dbmaster -p 3306:3306 -e MYSQL_ROOT_PASSWORD=tpl9 -e MYSQL_DATABASE=euromillions -d panamedia/devel-dbmaster
sudo docker exec -d devel-dbmaster /dbinit/init_database.sh

####
## BUILDING DEVEL-WEB CONTAINER
####
cd /docker/devel-web
sudo docker build -t="panamedia/devel-web" .
sudo docker stop devel-web
sudo docker rm devel-web
sudo docker run -v /src:/var/www/ -d -p 8080:80 --name devel-web --link devel-dbmaster:mysql panamedia/devel-web

