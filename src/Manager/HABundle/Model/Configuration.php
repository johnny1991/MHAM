<?php

namespace Manager\HABundle\Model;

class Configuration {

	public $conf;
	
	public function __construct(){
		echo $$this->get('kernel')->getRootDir();
		$this->conf = parse_ini_file($$this->get('kernel')->getRootDir() . '../scripts/HA.conf', 1, INI_SCANNER_RAW);
	}
	
	public function getConf(){
		return $this>conf;
	}
}