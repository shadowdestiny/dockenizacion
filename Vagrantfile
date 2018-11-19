Vagrant.configure(2) do |config|
    config.vm.provider "virtualbox" do |v|
        v.memory = 2048
    end

    config.vm.box = "ubuntu/bionic64"

    config.vm.provision "shell", inline: <<-SCRIPT
       DEBIAN_FRONTEND=noninteractive && apt-get update && apt-get upgrade -y \
       && apt-get autoremove \
       && apt-get clean && apt-get autoclean
    SCRIPT

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

    config.vm.provision "shell", run: "always", inline: <<-SCRIPT
        sudo vagrant \
        && cd /vagrant \
        && docker-compose -f docker-compose.yml -f docker-compose.override.yml -f docker-compose.vagrant.yml up -d --build
    SCRIPT

    config.vm.network "private_network", ip: "192.168.50.10"

    # Enable synced_folder with SMB driver: https://www.vagrantup.com/docs/synced-folders/smb.html
    config.vm.synced_folder ".", "/vagrant",
        type: "smb",
        #smb_username: "your username",
        #smb_password: "your password",
        mount_options: ["mfsymlinks,file_mode=0776,dir_mode=0777"]
end
