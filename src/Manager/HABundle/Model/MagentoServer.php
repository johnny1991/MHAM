<?php

namespace Manager\HABundle\Model;

use Manager\HABundle\Model\ManagerMHA;


class MagentoServer extends Server {

	public $local_xml;
	public $bdd_ip;
	public $scripts_path;

	#public function __construct($ip){
	#	parent::__construct($ip);
	#}

	public function update(){
		parent::update();

		if (!$this->getStatus()){
			return false;
		}
		$this->scripts_path = Configuration::getInstance()->getScriptsPath();
		$this->initLocalxml();
	}

	public function initLocalxml(){
		$local_xml_path = Configuration::getInstance()->getLocalXmlPath();
		$content = shell_exec($this->scripts_path . "getFile --user=root --ip={$this->getIp()} --path=$local_xml_path");
		if($content){
			$this->local_xml = simplexml_load_string($content);
			$this->bdd_ip = $this->local_xml->global->resources->default_setup->connection->host;
		}
	}

	public function getLocalXml(){
		return $this->local_xml;
	}
	
	public function isMaster(){
		return $this->isPublicIp();
	}
	
	public function getBddIp(){
		return $this->bdd_ip;
	}
	
	public function isPublicIp(){
		if (!$this->getStatus()){
			return false;
		}
		$ip = trim(shell_exec($this->scripts_path . "getPublicIp --user=root --ip={$this->getIp()}"));
		return ($ip == Configuration::getInstance()->getPublicIp()) ? true : false;
	}
	
}