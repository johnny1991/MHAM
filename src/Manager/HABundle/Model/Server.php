<?php

namespace Manager\HABundle\Model;

 abstract class Server {

	public $ip;
	public $status = null;

	public function __construct($ip){
		$this->ip = $ip;
		$this->update();
	}

	public function getIp(){
		return $this->ip;
	}

	public function setIp($ip){
		$this->ip = $ip;
		return $this;
	}

	public abstract function isMaster();
	
	public function getStatus(){
		return $this->status;
	}

	public function update(){
		if($this->getStatus() == null){
			error_log($this->ip);
			$number_of_request = 2;
			$time_between_request = 0.2;
			$this->status = exec("ping -c$number_of_request -i$time_between_request " . $this->getIp()) ? true : false;
		}
	}
	
}