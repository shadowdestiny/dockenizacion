Vagrant.configure(2) do |config|
    config.vm.provider "virtualbox" do |v|
        v.memory = 2048
        v.customize ["setextradata", :id, "VBoxInternal2/SharedFoldersEnableSymlinksCreate/vagrant", "1"]
    end

    config.vm.box = "ubuntu/bionic64"

#    config.vm.provision "shell", inline: <<-SCRIPT
#       DEBIAN_FRONTEND=noninteractive && apt-get update && apt-get upgrade -y \
#       && apt-get autoremove \
#       && apt-get clean && apt-get autoclean
#    SCRIPT

    config.vm.provision "ansible_local" do |ansible|
        ansible.playbook        = "devOps/playbook_local_setup.yml"
        ansible.limit           = "all"
        ansible.install_mode    = "pip"
        ansible.version         = "2.7.0"

        ansible.groups = {
            'localhost' => ['default']
        }

        ansible.extra_vars = {
          do_docker_setup: true,
          is_vagrant: true
        }
    end

    config.vm.network "private_network", ip: "192.168.50.10"
    #config.vm.synced_folder "src/", "/var/www", owner: "www-data", mount_options: ["dmode=775,fmode=774"]
    #config.vm.synced_folder "src/", "/var/www", owner: "www-data", mount_options: ["dmode=775,fmode=774"]

end
