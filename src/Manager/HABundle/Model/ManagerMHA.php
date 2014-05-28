<?php

namespace Manager\HABundle\Model;

class ManagerMHA {

	protected static $instance;
	
	public $status;
	
	public $mainBddServer = false;
	public $serversDown = false;
	public $isPublicLive = null;
	
	public $bddServers;
	public $magentoServers;
	
	public $mha;

	protected function __construct(){
		$this->mha = new MHA();
		
		$this->initBddServers();
		$this->initMagentoServers();
		echo "manager construct";
	}

	public function initMagentoServers(){
		foreach ($this->getConfiguration()->getMagentoIps() as $ip){
			$this->addMagentoServer(new MagentoServer($ip));
		}
	}
	
	public function initBddServers(){
		$countMaster = 0;
		foreach ($this->getConfiguration()->getBddIps() as $ip){
			$server = new BddServer($ip);
			$this->addBddServer($server);
			if($server->getMysql()->isMaster()){
				$this->mainBddServer = $server;
				$countMaster++;
			}
			if($server->getMysql()->getStatus() == false){
				$this->serversDown[] = $server;
			}
			unset($server);
		}
	}
	
	public static function getConfiguration(){
		return Configuration::getInstance();
	}
	
	public function getMha(){
		return $this->mha;
	}
	
	public function getMainBddServer(){
		return $this->mainBddServer;
	}

	public function isMainBddServerOperational(){
		if($this->getMainBddServer()){
			if($this->getMha()->getMainBddIp() == $this->getMainBddServer()->getMysql()->getIp()){
				return true;
			}
		}
		return false;
	}

	public function getBddServers(){
		return $this->bddServers;
	}
	
	public function getMagentoServers(){
		return $this->magentoServers;
	}

	public function getServersDown(){
		return $this->serversDown;
	}
	
	public function isPublicIpLive(){
		if($this->isPublicLive == null){
			$number_of_request = 2;
			$time_between_request = 0.2;
			exec("ping -c$number_of_request -i$time_between_request " . $this->getConfiguration()->getPublicIp(), $output, $result);
			$this->isPublicLive = ! $result;
		}
		return $this->isPublicLive;
	}

	public function addBddServer(BddServer $bddServer){
		$this->bddServers[] = $bddServer;
	}
	
	public function addMagentoServer(MagentoServer $magentoServer){
		$this->magentoServers[] = $magentoServer;
	}

	public static function getInstance(){
		if (!isset(self::$instance)){
			self::$instance = new self;
		}
		return self::$instance;
	}

}