# Instructions

## Installation of a developer machine
The pre-requisites for installation are:

1. [Vagrant](https://www.vagrantup.com/downloads.html)
2. [VirtualBox](https://www.virtualbox.org/wiki/Downloads)

If your are on windows do the following steps:

1. Install the [Git Extensions](https://code.google.com/p/gitextensions/)
2. Add the *bin* folder from the Git Extensions to the path

For all OS

1. Clone this repository: `git clone https://github.com/PanamediaSLU/devel-structure.git your-folder` (substitute _your-folder_ with the path you want to clone into)
2. Go into your folder on a command shell
3. If you are on mac/unix, execute command: `chmod 755 install-dev-environment.sh`
4. Execute command: `./install-dev-environment.sh` if you are on mac/unix, or  `install-dev-environment.bat` if you are on Windows


## Restarting the virtual development environment
After restarting the workstation, or when you want to refresh the environment after pulling changes from github:

1. Execute command  `chmod 755 restart-dev-environment.sh`
2. Execute command: `./restart-dev-environment.sh`