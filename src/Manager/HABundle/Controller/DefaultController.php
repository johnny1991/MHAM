<?php

namespace Manager\HABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
	public function indexAction(){
		$mha = '/etc/mha.conf';

		$user = trim(`cat $mha | grep user | awk '{print $3}'`);
		$password = trim(`cat $mha | grep password | awk '{print $3}'`);
		$ip_bdd = explode("\n", `cat $mha | grep hostname | awk '{print $3}'`);
		$ip_bdd = array_filter($ip_bdd);
		$data =array();
		if($tmp = `service mha_daemon status`){
			if (strpos($tmp, 'is not running') !== false) {
				$mha_status = false;
			} else if (strpos($tmp, 'is running') !== false) {
                                $mha_status = true;
                        }

		}

		foreach($ip_bdd as $ip){
			$array = $status = null;
			$isMaster = $this->isMaster($user, $password, $ip);
			$isMaster = $isMaster['variable_value'];
			if($isMaster == 'OFF'){
				$status = $this->getMasterStatus($user, $password, $ip);
			//	var_dump($status);
				$array = array('ip' => $ip,
						'status' => 'Master',
						'log_binaire' => $status['File'],
						'pos_binaire' => $status['Position'],
						'io_running' => ' ',
						'sql_running' => ' ');
				
			} elseif($isMaster == 'ON' ){
				$status = $this->getSlaveStatus($user, $password, $ip);
				
				//var_dump($status);
				$array = array('ip' => $ip,
						'status' => 'Slave',
						'log_binaire' => $status['Master_Log_File'],
						'pos_binaire' => $status['Read_Master_Log_Pos'],
						'io_running' => $status['Slave_IO_Running'],
						'sql_running' => $status['Slave_SQL_Running']);
				
				$log_binaire = `echo $status | awk -F":" '{ print $8 }' | awk -F" " '{ print $1 }'`;
				$pos_binaire = `echo $status | awk -F":" '{ print $7 }' | awk -F" " '{ print $1 }'`;
				$io_running = `echo $status | awk -F":" '{ print $12 }' | awk -F" " '{ print $1 }'`;
				$sql_running = `echo $status | awk -F":" '{ print $13 }' | awk -F" " '{ print $1 }'`;
			}

			if(! is_null($array)){
				array_push($data, $array);
			}
		}
	
		return $this->render('ManagerHABundle:Default:index.html.twig', array('bdd' => $data, 'mha_status' => $mha_status));
	}

	public function logAction(){
		$html = nl2br(shell_exec('tail -n 15 /var/log/masterha/MHA.log'));
		$response = new Response(json_encode($html));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}



	public function isMaster($user, $password, $ip) {
		$connection = new \PDO("mysql:host=$ip;dbname=inkia_nomyisam", $user, $password);
		$select = "select variable_value from information_schema.global_status where variable_name = 'Slave_running';";
		return $connection->query($select)->fetch();
	}

	public function getMasterStatus($user, $password, $ip) {
		$connection = new \PDO("mysql:host=$ip;dbname=inkia_nomyisam", $user, $password);
                $select = "SHOW MASTER STATUS";
                return $connection->query($select)->fetch();
	}

	public function getSlaveStatus($user, $password, $ip) {
		$connection = new \PDO("mysql:host=$ip;dbname=inkia_nomyisam", $user, $password);
		$select = "SHOW SLAVE STATUS";
                return $connection->query($select)->fetch();	
	}

}
