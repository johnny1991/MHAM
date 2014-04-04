<?php

namespace Manager\HABundle\Model;

class Server {

	public $ip;
	public $status;

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

	public function getStatus(){
		return $this->status;
	}

	public function update(){
		$this->status = exec("ping -c2 -i0.25 $this->getIp()") ? true : false;
	}
	
	public function getManager(){
		return ManagerMHA::getInstance();
	}

}