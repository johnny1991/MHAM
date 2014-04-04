<?php

namespace Manager\HABundle\Model;

class Configuration {

	public $scripts_path;
	public $configuration_path;
	public $configuration;

	public $bdd_ips;
	public $bdd_name;
	public $bdd_user;
	public $bdd_password;
	public $bdd_root_user;
	public $bdd_root_password;
	public $bdd_mask;
	public $bdd_conf_path;

	public $mha_conf_path;

	public $ip_slave_initial;
	public $ip_master_initial;

	public $magento_ips;
	public $local_xml_path;

	public function __construct(){
		$this->scripts_path = __DIR__ . '../../../../../scripts/';
		$this->configuration_path = $this->scripts_path . 'HA.conf';
		$this->configuration = parse_ini_file($this->configuration_path, 1, INI_SCANNER_RAW);

		$this->bdd_name = $this->configuration['db_name'];
		$this->bdd_user = $this->configuration['db_user'];
		$this->bdd_password = $this->configuration['db_password'];
		$this->bdd_root_user = $this->configuration['db_root'];
		$this->bdd_root_password = $this->configuration['db_root_password'];

		$this->bdd_conf_path = $this->configuration['mycnf'];
		$this->mha_conf_path = $this->configuration['mha_conf'];
		$this->local_xml_path = $this->configuration['local_xml'];

		$this->initial_slave_ip = $this->configuration['ip_slave'];
		$this->initial_master_ip = $this->configuration['ip_master'];
		$this->magento_ips = $this->configuration['ip_magento'];
		$this->bdd_ips = $this->configuration['ip_bdd'];
		$this->bdd_mask = $this->configuration['ip_all'];

		return $this->configuration;
	}

	public function getConfiguration(){
		return $this->configuration;
	}

	public function getConfigurationPath(){
		return $this->configuration_path;
	}
	
	public function getScriptsPath(){
		return $this->scripts_path;
	}

	public function getBddName(){
		return $this->bdd_name;
	}

	public function getBddUser(){
		return $this->bdd_user;
	}

	public function getBddPassword(){
		return $this->bdd_password;
	}

	public function getBddRootUser(){
		return $this->bdd_root_user;
	}

	public function getBddRootPassword(){
		return $this->bdd_root_password;
	}

	public function getBddConfPath(){
		return $this->bdd_conf_path;
	}

	public function getMhaConfPath(){
		return $this->mha_conf_path;
	}

	public function getLocalXmlPath(){
		return $this->local_xml_path;
	}

	public function getInitialSlaveIp(){
		return $this->initial_slave_ip;
	}

	public function getInitialMasterIp(){
		return $this->initial_master_ip;
	}

	public function getMagentoIps(){
		return $this->magento_ips;
	}

	public function getBddIps(){
		return $this->bdd_ips;
	}

	public function getBddMask(){
		return $this->bdd_mask;
	}

}