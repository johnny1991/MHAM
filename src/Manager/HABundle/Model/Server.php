<?php

namespace Manager\HABundle\Model;

class Server {

	public $ip;
	public $user;
	public $password;
	public $status;

	public function __construct($ip, $user, $password){
		$this->ip = $ip;
		$this->user = $user;
		$this->password = $password;
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
		$this->status = exec("ping -c2 -i0.25 $this->ip") ? true : false;
	}

}