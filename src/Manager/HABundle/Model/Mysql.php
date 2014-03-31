<?php

namespace Manager\HABundle\Model;

use Manager\HABundle\Model\ManagerMHA;

class Mysql {

	public $ip;
	public $user;
	public $password;

	public $status;
	public $state;
	public $isStateOk = true;
	public $replicationStatus;
	public $global;
	public $PDOinstance;

	public function __construct($ip, $user, $password){
		$this->ip = $ip;
		$this->user = $user;
		$this->password = $password;
		$this->update();
	}

	public function update(){
		$mhaConf = ManagerMHA::getConf();
		$this->isConnected();
		if($this->status){
			$this->PDOinstance = new \PDO("mysql:host=$this->ip;dbname=inkia_nomyisam", $this->user, $this->password,array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING));
			$this->getState(); // Manage Errors if possible
			$this->getReplicationStatus(); // Manage Errors if possible
			$this->getGlobal();
			$this->isStateOk = (($this->state == 'Master') && ($mhaConf['server1']['hostname'] == $this->ip));
		}
	}

	public function getIp(){
		return $this->ip;
	}

	public function setIp($ip){
		$this->ip = $ip;
		return $this;
	}

	public function isStateOk(){
		return $this->isStateOk();
	}
	
	public function getStatus(){
		return $this->status;
	}

	public function getState() {
		if(!$this->state){
			try {
				$result = $this->PDOinstance->query("select variable_value from information_schema.global_status where variable_name = 'Slave_running'")->fetch();
				$this->state = ($result['variable_value'] == 'ON') ? 'Master' : 'Slave';
			} catch (PDOException $e) {
				return $e;
			}
		}
		return $this->state;
	}

	public function getReplicationStatus() {
		if(!$this->replicationStatus){
			try {
				if($this->state == 'Master'){
					$this->replicationStatus = $this->PDOinstance->query("SHOW MASTER STATUS")->fetch();
				} else if($this->state == 'Slave'){
					$this->replicationStatus = $this->PDOinstance->query("SHOW SLAVE STATUS")->fetch();
					var_dump($this->PDOinstance->query("SHOW SLAVE STATUS")->fetch());
				}
			} catch(Exception $e) {
				return false;
			}
		}
		return $this->replicationStatus;
	}

	public function getGlobal() {
		if(!$this->global){
			try {
				$tmp = $this->PDOinstance->query("SHOW GLOBAL VARIABLES")->fetchAll(\PDO::FETCH_ASSOC);
				$result = array();
				foreach ($tmp as $item){
					$result[$item['Variable_name']] = $item['Value'];
				}
				$this->global = $result;
			} catch(Exception $e) {
				return false;
			}
		}
		return $this->global;
	}

	private function isConnected(){
		$mysqli = new \mysqli($this->ip, $this->user, $this->password);

		if (($mysqli->connect_errno) || (!$mysqli->ping())) {
			$this->status = false;
		} else {
			$this->status = true;
		}
		$mysqli->close();
	}

}