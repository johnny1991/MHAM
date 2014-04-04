<?php

namespace Manager\HABundle\Model;

class Configuration {

	public $conf;

	public function __construct(){
		$this->conf = parse_ini_file(__DIR__.'../../../../../scripts/HA.conf', 1, INI_SCANNER_RAW);
	}

	public function getConf(){
		return $this->conf;
	}
}