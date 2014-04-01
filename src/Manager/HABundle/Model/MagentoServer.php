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
		$this->localxml = simplexml_load_file(shell_exec("/home/installer_mha/getLocalXml --user=root --ip=$this->ip --path=$this->localxmlpath"));
	}

	public function getLocalXml(){
		return $this->localxml;
	}

}