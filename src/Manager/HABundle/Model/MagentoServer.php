<?php

namespace Manager\HABundle\Model;

use Manager\HABundle\Model\ManagerMHA;


class MagentoServer extends Server {

	public $local_xml;
	public $bdd_ip;

	#public function __construct($ip){
	#	parent::__construct($ip);
	#}

	public function update(){
		parent::update();
		$this->initLocalxml();
	}

	public function initLocalxml(){
		$local_xml_path = $this->getManager()->getConfiguration()->getLocalXmlPath();
		$scripts_path = $this->getManager()->getConfiguration()->getScriptsPath();
		$content = shell_exec($scripts_path . "getFile --user=root --ip=$this->getIp() --path=$local_xml_path");
		if($content){
			$this->local_xml = simplexml_load_string($content);
			$this->bdd_ip = $this->localxml->global->resources->default_setup->connection->host;
		}
	}

	public function getLocalXml(){
		return $this->local_xml;
	}
	
	public function getBddIp(){
		return $this->bdd_ip;
	}

}