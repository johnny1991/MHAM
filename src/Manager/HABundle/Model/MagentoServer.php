<?php

namespace Manager\HABundle\Model;

use Manager\HABundle\Model\ManagerMHA;


class MagentoServer extends Server {

	public $localxmlpath;
	public $localxml;
	
	public function __construct($ip, $user, $password){
		$this->localxmlpath = ManagerMHA::$localxmlpath;
		parent::__construct($ip, $user, $password);
	}

	public function update(){
		parent::update();
		$this->initLocalxml();
	}
	
	public function initLocalxml(){
		var_dump(exec("sudo ssh root@$ip 'cat ".$this->localxmlpath."'"));
		$this->localxml = exec("sudo ssh root@$ip 'cat ".$this->localxmlpath."'");
	}
	
	public function getLocalXml(){
		return $this->localxml;
	}

}