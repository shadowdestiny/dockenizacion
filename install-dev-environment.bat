mkdir .git/hooks
cp githooks/* .git/hooks
cd vagrant
vagrant plugin install vagrant-vbguest
vagrant up