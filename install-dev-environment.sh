#!/usr/bin/env bash
chmod 777 src/app/assets
chmod 777 src/app/cache
mkdir .git/hooks
cp githooks/* .git/hooks
cd vagrant
vagrant plugin install vagrant-vbguest
vagrant up; vagrant ssh -c 'sudo ln -s /opt/VBoxGuestAdditions-4.3.10/lib/VBoxGuestAdditions /usr/lib/VBoxGuestAdditions'; vagrant reload --provision
