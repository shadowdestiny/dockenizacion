Vagrant.configure(2) do |config|
    config.vm.provider "virtualbox" do |v|
        v.memory = 2048
    end
    config.vm.box = "ubuntu/vivid64"
#    config.vm.provision "shell", path: "vagrant_config/install_ansible.sh", privileged: false
#    config.vm.provision "shell", path: "vagrant_config/provision_with_ansible.sh", run: "always", privileged: false
    config.vm.provision "shell", path: "vagrant_config/bootstrap.sh", privileged: false
    config.vm.provision "shell", path: "vagrant_config/docker-compose.sh", run: "always", privileged: true
    config.vm.network "forwarded_port", guest: 8080, host: 8080
    config.vm.network "forwarded_port", guest: 3306, host: 33060
    config.vm.synced_folder "src/", "/var/www", owner: "www-data", mount_options: ["dmode=775,fmode=774"]
end
