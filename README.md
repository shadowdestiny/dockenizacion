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

Important: Git must be configured for checkout files as LF. If not sure you can run this commands (on the repository folder) if you have already cloned the repository:

```
git config core.eol lf
git config core.autocrlf input

git rm -rf --cached . 
git reset --hard HEAD
```

### Mac OS
Not tested in this OS.

## Steps for running the development environment:

### For all OS
1. Clone this repository: `git clone https://github.com/PanamediaSLU/euromillions your-folder` (substitute _your-folder_ with the path you want to clone into)
2. Copy the file .env.dist to .env
3. Go into your folder on a command shell
4. Set variable **'enable_frontend_react_build'** to **'true'** for the first run on file devOps/playbook_local_setup.yml 

### For Linux OS

1. Run **$ ansible-playbook devOps/playbook_local_setup.yml**
2. Edit /etc/hosts file for point dev.euromillions.com to IP defined at .env file with the value of ***EM_DOCKER_VARNISH_IP*** by default 172.10.10.10
3. You can connect to MySQL database on the ip defined with ***EM_DOCKER_DATABASE_IP***
4. After first boot, you can do a $ docker-compose up -d --build for get the environment running

### For Windows OS

1. $ vagrant up
2. Edit /etc/hosts file for point dev.euromillions.com to the Vagrant IP (192.168.50.10)
3. Use $ vagrant provision for a full reload of containers
4. Edit the file

- On every machine boot, Vagrant will ask for a username/password for mount de Share. This is your Windows user/password. 
Vagrant needs that info for mount the share inside the guess. 
We use SMB mount for handle symlinks ( node.js modules uses it for example)

#### Know Issues

- The first **$ vagrant up** will fail at task: **TASK [start docker compose (Windows OS Host)]**, so is needed to run again **$ vagrant provision** for finish the setup running again the provisioning

### Variables on devOps/playbook_local_setup.yml
- ***enable_frontend_react_build***: "bool" | Set to 'true' for enable the build of react folder on provision.
- ***is_vagrant***: "bool" | In VagrantFile we force to be 'yes' and we can manage conditional task on Windows Hosts.
- ***update_geoip_database***: "bool" | Forces the download of  the geoipdatabases even if the files exists.
- ***do_docker_setup***: "bool" | Set to true if you want to install docker dependencies on the machine. Localhost if you are on Linux or inside the Vagrant if you are on Windows. By default is set to 'true' on Vagrant (Windows Hosts)  

## Run frontend watchers

```
 $ ansible-playbook devOps/playbook_local_frontend.yml
```

* On Windows; enter before on the vagrant machine ( vagrant ssh )

This command will run 3 Docker containers:
- euromillions-compassweb-watcher
- euromillions-compassadmin-watcher 
- euromillions-react-watcher

You can view their logs via **$ docker log -f <container-name>**


