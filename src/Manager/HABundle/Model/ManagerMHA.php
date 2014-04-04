<?php

namespace Manager\HABundle\Model;

class ManagerMHA {

	protected static $instance;

	public static $file = '/etc/mha.conf';
	public static $conf;
	public static $localxmlpath = '/var/www/magento/app/etc/local.xml';
	public static $bdName = 'collectiong';
	public static $mhalog = '/var/log/masterha/MHA.log';

	public $user;
	public $password;
	public $bddIps;
	public $magentoIps;
	public $mainMhaBddIp;
	public $mainServerBdd = false;
	public $status;
	public $isMhaOk = false;
	public $isServerDown = false;
	public $bddServers;
	public $magentoServers;

	protected function __construct(){
		$this->magentoIps = array('172.20.0.213','172.20.0.234');
		$conf = $this->getConf();
		$this->user = $conf['server default']['user'];
		$this->password = $conf['server default']['password'];
		$this->mainMhaBddIp = $conf['server1']['hostname'];
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

		if($this->status){
			$last_line = shell_exec('tail -n 1 '. self::$mhalog);
			if (strpos($last_line, "Ping(SELECT) succeeded, waiting until MySQL doesn't respond..") !== false) {
				$this->isMhaOk = true;
			}
		}

		$this->initBddServers();
		$this->initMagentoServers();

	}

	public function getUser(){
		return $this->user;
	}

	public function getMainMhaBddIp(){
		return $this->mainMhaBddIp;
	}

	public function getMainServerBdd(){
		return $this->mainServerBdd;
	}

	public function isMasterOk(){
		if($this->mainServerBdd){
			if($this->mainMhaBddIp == $this->mainServerBdd->getMysql()->getIp()){
				return true;
			}
		}

		return false;
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

	public function getIsServerDown(){
		return $this->isServerDown;
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
		$countMaster = 0;
		foreach ($this->getBddIps() as $ip){
			$server = new BddServer($ip, $this->user, $this->password);
			$this->addBddServer($server);
			if($server->getMysql()->isMaster()){
				$this->mainServerBdd = $server;
				$countMaster++;
			}
			if($server->getMysql()->getStatus() == false){
				$this->isServerDown = true;
			}
			unset($server);
		}
		if($countMaster > 1){
			$this->mainServerBdd = false;
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