# -*- mode: ruby -*-
# vi: set ft=ruby :

require 'json'
require 'yaml'

VAGRANTFILE_API_VERSION ||= "2"
confDir = $confDir ||= File.expand_path(File.dirname(__FILE__))

homesteadYamlPath = confDir + "/Homestead.yaml"
homesteadJsonPath = confDir + "/Homestead.json"
afterScriptPath = confDir + "/after.sh"
aliasesPath = confDir + "/aliases"

require File.expand_path(File.dirname(__FILE__) + '/scripts/homestead.rb')

Vagrant.require_version '>= 1.9.0'

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    if File.exist? aliasesPath then
        config.vm.provision "file", source: aliasesPath, destination: "/tmp/bash_aliases"
        config.vm.provision "shell" do |s|
            s.inline = "awk '{ sub(\"\r$\", \"\"); print }' /tmp/bash_aliases > /home/vagrant/.bash_aliases"
        end
    end
    
    config.vm.synced_folder "~/Homestead/nwrfcsdk", "/usr/sap/nwrfcsdk"
    config.vm.synced_folder "~/Homestead/php7-sapnwrfc", "/php7-sapnwrfc"

    config.vm.provision "shell", inline: <<-SHELL
        export DEBIAN_FRONTEND=noninteractive
        echo -e "\n--- Updating packages list ---\n"
        apt-get -qq update

        echo -e "\n--- Installing php dev package ---\n"
        apt-get install -y php7.2-dev
        
        echo -e "\n--- Create symlink for php-config ---\n"
        rm /usr/bin/php-config
        ln -s /usr/bin/php-config7.2 /usr/bin/php-config
    
        echo -e "\n--- Preparing sapnwrfc extension ---\n"
     	cd /php7-sapnwrfc
     	phpize7.2
     	./configure
     	make 
     	make install
     	echo "/usr/sap/nwrfcsdk/lib" > /etc/ld.so.conf.d/nwrfcsdk.conf
     	ldconfig
     	ldconfig

        echo -e "\n--- Add sapnwrfc extension in php.ini ---\n"
        echo "extension=sapnwrfc.so" > /etc/php/7.2/cli/php.ini
    SHELL
       
    if File.exist? homesteadYamlPath then
        settings = YAML::load(File.read(homesteadYamlPath))
    elsif File.exist? homesteadJsonPath then
        settings = JSON::parse(File.read(homesteadJsonPath))
    else
        abort "Homestead settings file not found in #{confDir}"
    end

    Homestead.configure(config, settings)

    if File.exist? afterScriptPath then
        config.vm.provision "shell", path: afterScriptPath, privileged: false, keep_color: true
    end

    if Vagrant.has_plugin?('vagrant-hostsupdater')	
        config.hostsupdater.aliases = settings['sites'].map { |site| site['map'] }
    elsif Vagrant.has_plugin?('vagrant-hostmanager')
        config.hostmanager.enabled = true
        config.hostmanager.manage_host = true
        config.hostmanager.aliases = settings['sites'].map { |site| site['map'] }
    end
end
