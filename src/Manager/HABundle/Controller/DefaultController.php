<?php

namespace Manager\HABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
	public function indexAction(){
		$mha = '/etc/mha.conf';
		$mha_conf = parse_ini_file($mha,1,INI_SCANNER_RAW);

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
			$status = null;
			$isMaster = $this->isMaster($user, $password, $ip);
			$isMaster = $isMaster['variable_value'];

			$array = array();
			$array['ip'] = $ip;
			$array['global_variables'] = $this->getVariables($user, $password, $ip);
			$array['server_status'] = exec("ping ".$ip." -w 2") ? true : false;
			
			if($isMaster == 'OFF'){
				$status = $this->getMasterStatus($user, $password, $ip);
				$array['status'] = 'Master';
				$array['log_binaire'] = $status['File'];
				$array['pos_binaire'] = $status['Position'];
				$array['io_running'] = ' ';
				$array['sql_running'] = ' ';
				
			} elseif($isMaster == 'ON' ){
				$status = $this->getSlaveStatus($user, $password, $ip);
				$array['status'] = 'Slave';
				$array['log_binaire'] = $status['Master_Log_File'];
				$array['pos_binaire'] = $status['Read_Master_Log_Pos'];
				$array['io_running'] = $status['Slave_IO_Running'];
				$array['sql_running'] = $status['Slave_SQL_Running'];
			}

			array_push($data, $array);
		}

		return $this->render('ManagerHABundle:Default:index.html.twig', array('bdd' => $data, 'mha_status' => $mha_status, 'mha_conf' => $mha_conf));
	}

	public function logAction(){
		$html = nl2br(shell_exec('tail -n 15 /var/log/masterha/MHA.log'));
		$response = new Response(json_encode($html));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

	public function chaAction(){
		$html = nl2br(shell_exec('tail -n 15 /var/log/masterha/MHA.log'));
		$response = new Response(json_encode($html));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

	public function isMaster($user, $password, $ip) {
		if($this->isConnected($user, $password, $ip)) {
			try {
				$connection = new \PDO("mysql:host=$ip;dbname=inkia_nomyisam", $user, $password,array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING));
				$select = "select variable_value from information_schema.global_status where variable_name = 'Slave_running';";
				return $connection->query($select)->fetch();
			} catch (PDOException $e) {
				return false;
			}
		}
		return false;
	}

	public function getMasterStatus($user, $password, $ip) {
		if($this->isConnected($user, $password, $ip)) {
			try {
				$connection = new \PDO("mysql:host=$ip;dbname=inkia_nomyisam", $user, $password);
				$select = "SHOW MASTER STATUS";
				return $connection->query($select)->fetch();
			} catch(Exception $e) {
				return false;
			}
		}
		return false;
	}

	public function getSlaveStatus($user, $password, $ip) {
		if($this->isConnected($user, $password, $ip)) {
			try {
				$connection = new \PDO("mysql:host=$ip;dbname=inkia_nomyisam", $user, $password);
				$select = "SHOW SLAVE STATUS";
				return $connection->query($select)->fetch();
			} catch(Exception $e) {
				return false;
			}
		}
		return false;
	}

	public function getVariables($user, $password, $ip) {
		if($this->isConnected($user, $password, $ip)) {
			try {
				$connection = new \PDO("mysql:host=$ip;dbname=inkia_nomyisam", $user, $password);
				$select = "SHOW GLOBAL VARIABLES";
				$tmp = $connection->query($select)->fetchAll(\PDO::FETCH_ASSOC);
				$result = array();
				foreach ($tmp as $item){
					$result[$item['Variable_name']] = $item['Value'];
				}
				return $result;
			} catch(Exception $e) {
				return false;
			}
		}
		return false;
	}

	public function isConnected($user, $password, $ip){
		$mysqli = new \mysqli($ip, $user, $password);
		$result = true;

		if ($mysqli->connect_errno) {
			$result = false;
		}

		if (!$mysqli->ping()) {
			$result = false;
		}

		$mysqli->close();
		return $result;
	}
}
