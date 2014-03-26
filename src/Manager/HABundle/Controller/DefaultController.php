<?php

namespace Manager\HABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
	public function indexAction(){
		$mha = '/etc/mha.conf';

		$user = `cat $mha | grep user | awk '{print $3}'`;
		$password = `cat $mha | grep password | awk '{print $3}'`;
		$ip_bdd = explode("\n", `cat $mha | grep hostname | awk '{print $3}'`);
		$ip_bdd = array_filter($ip_bdd);
		$data =array();

		foreach($ip_bdd as $ip){
			$array = $status = null;
			$isMaster = isMaster($ip);
				
			if($isMaster == 'OFF'){
				$status = getMasterStatus($ip);
				var_dump($status);
				$array = array('ip' => $ip,
						'status' => 'Master',
						'log_binaire' => 'Maria-00097.bin',
						'pos_binaire' => '548752',
						'io_running' => ' ',
						'sql_running' => ' ');
				
			} elseif($isMaster == 'ON' ){
				$status = getSlaveStatus ($ip);
				
				var_dump($status);
				$array = array('ip' => $ip,
						'status' => 'Master',
						'log_binaire' => 'Maria-00097.bin',
						'pos_binaire' => '548752',
						'io_running' => ' ',
						'sql_running' => ' ');
				
				$log_binaire = `echo $status | awk -F":" '{ print $8 }' | awk -F" " '{ print $1 }'`;
				$pos_binaire = `echo $status | awk -F":" '{ print $7 }' | awk -F" " '{ print $1 }'`;
				$io_running = `echo $status | awk -F":" '{ print $12 }' | awk -F" " '{ print $1 }'`;
				$sql_running = `echo $status | awk -F":" '{ print $13 }' | awk -F" " '{ print $1 }'`;
			}

			if(! is_null($array)){
				array_push($data, $array);
			}
		}
			
		return $this->render('ManagerHABundle:Default:index.html.twig', array('bdd' => $data));
	}

	public function logAction(){
		$html = nl2br(shell_exec('tail -n 15 /var/log/masterha/MHA.log'));
		$response = new Response(json_encode($html));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}



	public function isMaster($user, $password, $ip) {
		return `mysql -h $ip -u $user -p$password -N -s -e "select variable_value from information_schema.global_status where variable_name = 'Slave_running';"`;
	}

	public function getMasterStatus($user, $password, $ip) {
		return `mysql -h $ip -u $user -p$password -N -s -e "SHOW MASTER STATUS;"`;
	}

	public function getSlaveStatus($user, $password, $ip) {
		return `mysql -h $ip -u $user -p$password -e "SHOW SLAVE STATUS\G;"`;
	}

}
