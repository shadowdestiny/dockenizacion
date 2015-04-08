#!/usr/bin/env bash
dockerv=$(docker -v 2>&1)
if [[ $dockerv != "Docker version 1.5.0"* ]]
then
  echo "NO TENEMOS DOCKER, INSTALANDO";
  wget -qO- https://get.docker.com/ | sh
fi
docker login --email="antonio.hernandez@panamedia.net" --username="panamedia" --password="2Fsz8EGmy6LhKCR5"

#build dev-www
cd /docker/devel-web
sudo docker build -t="panamedia/devel-web" .
sudo docker stop devel-web
sudo docker rm devel-web
sudo docker run -d -p 8080:80 --name devel-web panamedia/devel-web
