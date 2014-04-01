<?php

namespace Manager\HABundle\Model;

use Manager\HABundle\Model\ManagerMHA;


class MagentoServer extends Server {

	public $localxmlpath;
	public $localxml;
	public $BddIp;

	public function __construct($ip, $user, $password){
		$this->localxmlpath = ManagerMHA::$localxmlpath;
		parent::__construct($ip, $user, $password);
	}

	public function update(){
		parent::update();
		$this->initLocalxml();
	}

	public function initLocalxml(){
		$string = shell_exec("/home/installer_mha/getLocalXml --user=root --ip=$this->ip --path=$this->localxmlpath");
		$this->localxml = simplexml_load_string($string);
		$this->BddIp = $this->localxml->global->resources->default_setup->connection->host;
		print_r($this->localxml);
	}

	public function getLocalXml(){
		return $this->localxml;
	}
	
	public function getBddIp(){
		return $this->BddIp;
	}

}