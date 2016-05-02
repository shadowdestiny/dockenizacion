Vagrant.configure(2) do |config|
    config.vm.provider "virtualbox" do |v|
        v.memory = 2048
        v.customize ["setextradata", :id, "VBoxInternal2/SharedFoldersEnableSymlinksCreate/vagrant", "1"]
    end
    config.vm.box = "ubuntu/wily64"

    config.vm.provision "shell", inline: <<-SCRIPT
        sudo wget https://launchpad.net/~ansible/+archive/ubuntu/ansible-1.9/+files/ansible_1.9.4-1ppa~trusty_all.deb -O /tmp/ansible.deb
        sudo apt-get install -y python-support \
          python-jinja2 \
          python-yaml \
          python-paramiko \
          python-httplib2 \
          python-crypto sshpass
          dpkg -i /tmp/ansible.deb
          rm -f /tmp/ansible.deb
        ansible-galaxy install -r /vagrant/vagrant_config/requirements.yml --ignore-errors
    SCRIPT

    config.vm.provision "ansible_local" do |ansible|
        ansible.playbook    = "/vagrant/vagrant_config/provision.yml"
    end

    config.vm.provision "ansible_local", run: "always" do |ansible|
        ansible.playbook    = "/vagrant/vagrant_config/bootstrap.yml"
    end

    config.vm.provision "ansible_local", run: "always" do |ansible|
        ansible.provisioning_path   = "/vagrant/src/config_tpl"
        ansible.playbook            = "create_config.yml"
    end

    config.vm.network "forwarded_port", guest: 80, host: 8080
    config.vm.network "forwarded_port", guest: 443, host: 4433
    config.vm.network "forwarded_port", guest: 3306, host: 33060
    config.vm.synced_folder "src/", "/var/www", owner: "www-data", mount_options: ["dmode=775,fmode=774"]
end
