#!/usr/bin/env bash
function e {
    echo "####################################"
    echo $1
    echo "####################################"
}

e "STARTING ANSIBLE PROVISION"
ansible-playbook /vagrant/ansible/vagrant_provision.yml -c local
