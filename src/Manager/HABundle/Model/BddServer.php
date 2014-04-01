<?php

namespace Manager\HABundle\Model;

use Manager\HABundle\Model\Mysql;

class BddServer extends Server {

	public $mysql;

	public function __construct($ip, $user, $password){
		parent::__construct($ip, $user, $password);
	}

	public function update(){
		parent::update();
		$this->initMysql();
	}

	public function getMysql(){
		return $this->mysql;
	}

	public function initMysql(){
		$this->mysql = new Mysql($this->ip, $this->user, $this->password);
	}

}