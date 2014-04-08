<?php

namespace Manager\HABundle\Model;

class MHA {

	public $configuration_path;
	public $configuration;
	public $log_path;
	public $user;
	public $password;
	public $manager_workdir;
	public $master_ip_failover_script;
	public $bdd_ips;
	public $main_bdd_ip;
	public $slave_bdd_ip;

	public $isRunning = false;
	public $isOperational = false;

	public function __construct(){
		$this->configuration_path = Configuration::getInstance()->getMhaConfPath();
		$this->configuration = parse_ini_file($this->configuration_path, 1, INI_SCANNER_RAW);
		$this->log_path = $this->configuration['server default']['manager_log'];
		$this->user = $this->configuration['server default']['user'];
		$this->password = $this->configuration['server default']['password'];
		$this->manager_workdir = $this->configuration['server default']['manager_workdir'];
		$this->master_ip_failover_script = $this->configuration['server default']['master_ip_failover_script'];
		$this->main_bdd_ip = $this->configuration['server1']['hostname'];
		var_dump($this->configuration);
		$this->slave_bdd_ip = $this->configuration['server2']['hostname'];

		foreach($this->configuration as $item){
			if (!empty($item['hostname'])){
				$this->bdd_ips[] = $item['hostname'];
			}
		}

		if($tmp = `service mha_daemon status`){
			if (strpos($tmp, 'is not running') !== false) {
				$this->isRunning = false;
			} else if (strpos($tmp, 'is running') !== false) {
				$this->isRunning = true;
			}
		}
		
		if($this->isRunning){
			$last_line = shell_exec('tail -n 1 '. $this->log_path);
			if (strpos($last_line, "Ping(SELECT) succeeded, waiting until MySQL doesn't respond..") !== false) {
				$this->isOperational = true;
			}
		}

	}

	public function getConfiguration(){
		return $this->configuration;
	}

	public function getConfigurationPath(){
		return $this->configuration_path;
	}

	public function getLogPath(){
		return $this->log_path;
	}

	public function getUser(){
		return $this->user;
	}

	public function getPassword(){
		return $this->password;
	}

	public function getWorkdir(){
		return $this->manager_workdir;
	}

	public function getIpFailoverScript(){
		return $this->master_ip_failover_script;
	}
	
	public function getBddIps(){
		return $this->bdd_ips;
	}

	public function getMainBddIp(){
		return $this->main_bdd_ip;
	}
	
	public function getSlaveBddIp(){
		return $this->slave_bdd_ip;
	}
	
	public function stop(){
		exec('sudo /etc/init.d/mha_daemon stop');
	}
	
	public function start(){
		exec('sudo /etc/init.d/mha_daemon start');
	}
	
	public function restart(){
		exec('sudo /etc/init.d/mha_daemon restart');
	}
	
	public function status(){
		return exec('sudo /etc/init.d/mha_daemon status');
	}

	public function isRunning(){
		return $this->isRunning;
	}
	
	public function isOperational(){
		return $this->isOperational;
	}
	
}
