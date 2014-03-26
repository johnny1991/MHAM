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
			$array1 = array('ip' => '172.20.0.225',
				'status' => 'Master',
				'log_binaire' => 'Maria-00097.bin',
				'pos_binaire' => '548752',
				'io_running' => ' ',
				'sql_running' => ' ');
			
			array_push($data, $array1);
		} 
		/*
		$array2 = array('ip' => '172.20.0.236',
				'status' => 'Slave',
				'log_binaire' => 'Maria-00097.bin',
				'pos_binaire' => '548752',
				'io_running' => 'Yes',
				'sql_running' => 'Yes');
		*/
		//$array = array($array1, $array2);
		 
		return $this->render('ManagerHABundle:Default:index.html.twig', array('bdd' => $data));
	}

	public function logAction(){
		$html = nl2br(shell_exec('tail -n 15 /var/log/masterha/MHA.log'));
		$response = new Response(json_encode($html));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

public function isMaster() {
		return `mysql -h $1 -u $user -p$password -N -s -e "select variable_value from information_schema.global_status where variable_name = 'Slave_running';"`;
	}

	public function getMasterStatus() {
		return `mysql -h $1 -u $user -p$password -N -s -e "SHOW MASTER STATUS;"`;
	}

	public function getSlaveStatus() {
		return `mysql -h $1 -u $user -p$password -e "SHOW SLAVE STATUS\G;"`;
	}

}
