Vagrant.configure(2) do |config|
    config.vm.provider "virtualbox" do |v|
        v.memory = 2048
        v.customize ["setextradata", :id, "VBoxInternal2/SharedFoldersEnableSymlinksCreate/vagrant", "1"]
    end
    config.vm.box = "ubuntu/wily64"

    config.vm.provision "shell", inline: <<-SCRIPT
        sudo apt-get install python-software-properties
        sudo apt-get update
        sudo apt-get install python-pip python-dev -y
        sudo pip install ansible==1.9.4
        ansible-galaxy install -r /vagrant/vagrant_config/requirements.yml --ignore-errors

    SCRIPT
    config.vm.provision "ansible_local" do |ansible|
        ansible.playbook    = "/vagrant/vagrant_config/playbook.yml"
        ansible.install     = true
    end
#    config.vm.provision "shell", path: "vagrant_config/provision.sh", privileged: false
#    config.vm.provision "shell", path: "vagrant_config/docker-compose.sh", run: "always", privileged: true
#    config.vm.provision "shell", path: "vagrant_config/bootstrap.sh", run: "always", privileged: false
    config.vm.network "forwarded_port", guest: 80, host: 8080
    config.vm.network "forwarded_port", guest: 443, host: 4433
    config.vm.network "forwarded_port", guest: 3306, host: 33060
    config.vm.synced_folder "src/", "/var/www", owner: "www-data", mount_options: ["dmode=775,fmode=774"]
end
