# Vagrantfile for Apache Solr 9 Masterclass Lab

def define_solr_box(config, name, ip, mem, cpu, &block)
  config.vm.define name do |node|
    node.vm.box = "bento/ubuntu-22.04"
    node.vm.hostname = name
    node.vm.network "private_network", ip: ip
    
    node.vm.provider "virtualbox" do |vb|
      vb.memory = mem
      vb.cpus = cpu
    end

    # Common provisioning for Solr nodes
    node.vm.provision "shell", inline: <<-SHELL
      export DEBIAN_FRONTEND=noninteractive
      apt-get update
      apt-get install -y openjdk-17-jdk wget curl net-tools vim git php-cli php-curl

      # Download and Install Solr 9.6.0
      cd /tmp
      SOLR_VERSION="9.6.0"
      if [ ! -f "solr-${SOLR_VERSION}.tgz" ]; then
        wget -q https://archive.apache.org/dist/solr/solr/${SOLR_VERSION}/solr-${SOLR_VERSION}.tgz
      fi
      
      tar xzf solr-${SOLR_VERSION}.tgz solr-${SOLR_VERSION}/bin/install_solr_service.sh --strip-components=2
      bash ./install_solr_service.sh solr-${SOLR_VERSION}.tgz -i /opt -d /var/solr -u solr -s solr -p 8983 -n

      # Allow external connections by binding to all interfaces
      echo 'SOLR_JETTY_HOST="0.0.0.0"' >> /etc/default/solr.in.sh
      
      systemctl restart solr
      echo "Solr ${SOLR_VERSION} installed and running on ${ip}:8983"
    SHELL

    yield(node) if block_given?
  end
end

Vagrant.configure("2") do |config|

  # --- SOLR NODE 01 ---
  define_solr_box(config, "solr-01", "192.168.56.30", 2048, 2) do |node|
    node.vm.provision "shell", inline: <<-SHELL
      echo "Configuring Solr-01 as primary node..."
    SHELL
  end

  # --- SOLR NODE 02 ---
  define_solr_box(config, "solr-02", "192.168.56.31", 2048, 1) do |node|
    node.vm.provision "shell", inline: <<-SHELL
      echo "Configuring Solr-02 as secondary node..."
    SHELL
  end

end
