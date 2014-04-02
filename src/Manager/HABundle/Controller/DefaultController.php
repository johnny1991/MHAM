<?php

namespace Manager\HABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Manager\HABundle\Model\ManagerMHA;

class DefaultController extends Controller
{
	public function indexAction(){
		$ManagerMHA = ManagerMHA::getInstance();
		return $this->render('ManagerHABundle:Default:index.html.twig', array('manager' => $ManagerMHA));
	}

	public function logAction(){
		$html = nl2br(shell_exec('tail -n 15 /var/log/masterha/MHA.log'));
		$response = new Response(json_encode($html));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

	public function syncAction(){
		$ManagerMHA = ManagerMHA::getInstance();
		$conf = $ManagerMHA::getConf();
		$hostname = $conf['server1']['hostname'];
		
		if ($hostname == '172.20.0.225'){
			echo exec('/usr/bin/sudo /bin/bash /home/installer_mha/auto-reverse');
		} elseif ($hostname == '172.20.0.236'){
			echo exec('/usr/bin/sudo /bin/bash /home/installer_mha/auto-rereverse');
		}
		
		return new response(true);
	}

	public function start_mhaAction(){
		exec('sudo /etc/init.d/mha_daemon restart');
		return new response();
	}
}
