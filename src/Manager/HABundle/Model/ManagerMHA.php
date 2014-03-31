<?php

namespace Manager\HABundle\Model;

class ManagerMHA {

	protected static $instance;

	public static $file = '/etc/mha.conf';
	public static $conf;
	public $user;
	public $password;
	public $ips;
	public $mainIp;
	public $status;
	public $servers;

	protected function __construct(){
		$mha = '/etc/mha.conf';
		var_dump($this->getConf());
		$this->user = trim(`cat $mha | grep user | awk '{print $3}'`);
		$this->password = trim(`cat $mha | grep password | awk '{print $3}'`);
		$this->ips = explode("\n", `cat $mha | grep hostname | awk '{print $3}'`);
		$this->ips = array_filter($this->ips);

		if($tmp = `service mha_daemon status`){
			if (strpos($tmp, 'is not running') !== false) {
				$this->status = false;
			} else if (strpos($tmp, 'is running') !== false) {
				$this->status = true;
			}
		}

		$this->initServers();

	}

	public function getUser(){
		return $this->user;
	}

	public function getMainIp(){
		return $this->mainIp;
	}

	public function getServers(){
		return $this->servers;
	}

	public function getIps(){
		return $this->ips;
	}

	
	public static function getConf(){
		if(!self::$conf){
		self::$conf = parse_ini_file(self::$file, 1, INI_SCANNER_RAW);
		}
		return self::$conf;
	}

	public function initServers(){
		foreach ($this->getIps() as $ip){
			$this->addServer(new Server($ip, $this->user, $this->password));
		}
	}

	public function addServer($server){
		$this->servers[] = $server;
	}

	public function getServerByIp($ip){
		foreach($this->getServers() as $server){
			if($server->getIp() == $ip){
				return $server;
			}
		}
		return false;
	}

	public static function getInstance(){
		if (!isset(self::$instance))
		{
			self::$instance = new self;
		}

		return self::$instance;
	}

}