#!/usr/bin/env bash
function e {
    echo "####################################"
    echo $1
    echo "####################################"
}
e "INSTALLING ANSIBLE REQUIREMENTS"
ansible-galaxy install -r /vagrant/ansible/requirements.yml --force
e "STARTING ANSIBLE PROVISION"
ansible-playbook /vagrant/ansible/vagrant_provision.yml -c local -v
