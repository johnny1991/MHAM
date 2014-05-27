<?php

namespace Manager\HABundle\Model;

 abstract class Server {

	public $ip;
	public $status = null;

	public function __construct($ip){
		$this->ip = $ip;
		$this->update();
	}

	public function getIp(){
		return $this->ip;
	}

	public function setIp($ip){
		$this->ip = $ip;
		return $this;
	}

	public abstract function isMaster();
	
	public function getStatus(){
		return $this->status;
	}

	public function update(){
		if($this->getStatus() == null){
			$number_of_request = 2;
			$time_between_request = 0.2;
			exec("ping -c$number_of_request -i$time_between_request " . $this->getIp(), $output, $result);
			var_dump($result);
			$this->status = ! $result;//(bool) strpos($ping , "0% packet loss");
			//echo $this->ip . ' : ' . $this->status . "<br>";
		}
	}
	
}