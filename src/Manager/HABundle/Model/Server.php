<?php

namespace Manager\HABundle\Model;

use Manager\HABundle\Model\Mysql;

class Server {

	public $ip;
	public $status;
	public $mysql;

	public function __construct($ip){
		$this->ip = $ip;
		update();
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
		$this->status = exec("ping ".$ip." -w 2") ? true : false;
	}

	public function getMysql(){
		return $this->mysql;
	}

	public function setMysql(){
		$this->mysql = new Mysql($this->ip);
	}

}