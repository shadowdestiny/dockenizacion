# Instructions

## Installation of a developer machine
The pre-requisites for installation are:

1. [Vagrant](https://www.vagrantup.com/downloads.html)
2. [VirtualBox](https://www.virtualbox.org/wiki/Downloads)

Once you have them installed, on a terminal...

1. Clone this repository: `git clone https://github.com/PanamediaSLU/devel-structure.git your-folder` (substitute _your-folder_ with the path you want to clone into)
2. Enter the your-folder: `cd your-folder`
3. Execute command: `chmod 755 install-dev-environment.sh`
4. Execute command: `./install-dev-environment.sh`


## Restarting the virtual development environment
After restarting the workstation, or when you want to refresh the environment after pulling changes from github:
1. Execute command  `chmod 755 restart-dev-environment.sh`
2. Execute command: `./restart-dev-environment.sh`