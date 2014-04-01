<?php

namespace Manager\HABundle\Model;

class ManagerMHA {

	protected static $instance;

	public static $file = '/etc/mha.conf';
	public static $conf;
	public static $localxmlpath = '/var/www/magento/app/etc/local.xml';
	
	public $user;
	public $password;
	public $bddIps;
	public $magentoIps;
	//public $mainIp;
	public $status;
	public $bddServers;
	public $magentoServers;

	protected function __construct(){
		$this->magentoIps = array('172.20.0.213','172.20.0.234');
		$conf = $this->getConf();
		$this->user = $conf['server default']['user'];
		$this->password = $conf['server default']['password'];
		foreach($conf as $item){
			if (!empty($item['hostname'])){
				$this->bddIps[] = $item['hostname'];
			}
		}

		if($tmp = `service mha_daemon status`){
			if (strpos($tmp, 'is not running') !== false) {
				$this->status = false;
			} else if (strpos($tmp, 'is running') !== false) {
				$this->status = true;
			}
		}

		$this->initBddServers();
		$this->initMagentoServers();

	}

	public function getUser(){
		return $this->user;
	}

	public function getMainIp(){
		return $this->mainIp;
	}

	public function getBddServers(){
		return $this->bddServers;
	}

	public function getBddIps(){
		return $this->bddIps;
	}
	
	public function getMagentoIps(){
		return $this->magentoIps;
	}
	
	public static function getConf(){
		if(!self::$conf){
		self::$conf = parse_ini_file(self::$file, 1, INI_SCANNER_RAW);
		}
		return self::$conf;
	}

	public function initMagentoServers(){
		foreach ($this->getMagentoIps() as $ip){
			$this->addMagentoServer(new MagentoServer($ip, $this->user, $this->password));
		}
	}
	
	public function addMagentoServer($magentoServer){
		$this->magentoServers[] = $magentoServer;
	}
	
	public function initBddServers(){
		foreach ($this->getBddIps() as $ip){
			$this->addBddServer(new BddServer($ip, $this->user, $this->password));
		}
	}

	public function addBddServer($bddServer){
		$this->bddServers[] = $bddServer;
	}

	/*public function getBddServerByIp($ip){
		foreach($this->getBddServers() as $bddServer){
			if($bddServer->getIp() == $ip){
				return $bddServer;
			}
		}
		return false;
	}*/

	public static function getInstance(){
		if (!isset(self::$instance))
		{
			self::$instance = new self;
		}

		return self::$instance;
	}

}