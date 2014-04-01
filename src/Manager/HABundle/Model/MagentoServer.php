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
		$conn = ssh2_connect($this->ip, 22);
		ssh2_auth_pubkey_file(
				$conn,
				'root',
				'/root/.ssh/id_rsa.pub',
				'/root/.ssh/id_rsa'
		);
		
		$this->localxml = ssh2_exec("cat $this->localxmlpath");
	}
	
	public function getLocalXml(){
		return $this->localxml;
	}

}