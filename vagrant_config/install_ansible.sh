#!/usr/bin/env bash
function e {
    echo "####################################"
    echo $1
    echo "####################################"
}

sudo export DEBIAN_FRONTEND=noninteractive

e "INSTALLING ANSIBLE ON VAGRANT HOST"
sudo apt-get install -y software-properties-common
sudo apt-add-repository ppa:ansible/ansible
sudo apt-get update
sudo apt-get install -y ansible
