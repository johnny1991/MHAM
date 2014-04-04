<?php

namespace Manager\HABundle\Model;


class BddServer extends Server {

	public $mysql;
	public $bdd_user;
	public $bdd_password;

	public function __construct($ip){
		$this->bdd_user = Configuration::getInstance()->getUser();
		$this->bdd_password = Configuration::getInstance()->getPassword();
		parent::__construct($ip);
	}

	public function update(){
		parent::update();
		$this->initMysql();
	}

	public function getBddUser(){
		return $this->bdd_user;
	}
	
	public function getBddPassword(){
		return $this->bdd_password;
	}
	
	public function getMysql(){
		return $this->mysql;
	}

	public function initMysql(){
		$this->mysql = new Mysql($this->getIp(), $this->getBddUser(), $this->getBddPassword());
	}
	
}