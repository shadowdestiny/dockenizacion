[![Build Status](https://scrutinizer-ci.com/g/PanamediaSLU/euromillions/badges/build.png?b=master&s=b5bfef5cfbcf10eb16a5dec22ffa0cbda6583fa0)](https://scrutinizer-ci.com/g/PanamediaSLU/euromillions/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/PanamediaSLU/euromillions/badges/coverage.png?b=master&s=3c7cc5d1328fe1325537b9689787b961203b8455)](https://scrutinizer-ci.com/g/PanamediaSLU/euromillions/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/PanamediaSLU/euromillions/badges/quality-score.png?b=master&s=30e09efd0c8f4d9cbd919fd3c9d4614a1244620a)](https://scrutinizer-ci.com/g/PanamediaSLU/euromillions/?branch=master)

# TODO: This README is not updated! 

# Instructions

## Installation of a developer machine

The pre-requisites for installation are:

### Linux 
1. [Ansible](https://www.ansible.com/) Version >= 2.7
2. [Docker](https://docs.docker.com/install/)  Version >= 18.x CE
3. [Docker Compose](https://docs.docker.com/compose/)

On Linux we interact directly with Docker and we use Ansible for provision and configure the localhost machine.

***Linux SHOULD BE the main OS for develop this project***  

### Windows OS 
1. [Vagrant](https://www.vagrantup.com/downloads.html) Version >= 2.2.x
2. [VirtualBox](https://www.virtualbox.org/wiki/Downloads) Version >= 5.2.22

On Windows we wrap the Ansible / Docker approach for Linux with a Vagrant / Virtualbox machine. With this approach the automation with Ansible works in both
systems. The Windows OS support is limited and has a lot of inconvenience ( slow, hard to debug code, etc...) 

### Mac OS
Not tested in this OS.

## Steps for running the development environment:

### For all OS
1. Clone this repository: `git clone https://github.com/PanamediaSLU/devel-structure.git your-folder` (substitute _your-folder_ with the path you want to clone into)
2. Copy the file .env.dist to .env
3. Go into your folder on a command shell

### For Linux OS

1. $ ansible-playbook devOps/playbook_local_setup.yml
2. Edit /etc/hosts file for point dev.euromillions.com to IP defined at .env file with the value of ***EM_DOCKER_VARNISH_IP*** by default 172.10.10.10
3. You can connect to MySQL database on the ip defined with ***EM_DOCKER_DATABASE_IP***
4. After first boot, you can do a $ docker-compose -d --build for get the environment running


### For Windows OS

1. $ vagrant up
2. Edit /etc/hosts file for point dev.euromillions.com to the Vagrant IP (192.168.50.10)
3. Use $ vagrant provision for a full reload of containers
4. Edit the file 

- On every machine boot, Vagrant will ask for a username/password for mount de Share. This is your Windows user/password. 
Vagrant needs that info for mount the share inside the guess. 
We use SMB mount for handle symlinks ( node.js modules uses it for example)

### Variables on devOps/playbook_local_setup.yml
- ***is_vagrant***: "bool" | In VagrantFile we force to be 'yes' and we can manage conditional task on Windows Hosts.
- ***enable_frontend_watchers***: "bool" | Set to 'true' for enable the ***watchers containers*** for re-compile css/sass and react files.
- ***update_geoip_database***: "bool" | Forces the download of  the geoipdatabases even if the files exists.
- ***do_docker_setup***: "bool" | Set to true if you want to install docker dependencies on the machine. Localhost if you are on Linux or inside the Vagrant if you are on Windows. By default is set to 'true' on Vagrant (Windows Hosts)  
