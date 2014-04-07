<?php

namespace Manager\HABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Manager\HABundle\Model\ManagerMHA;
use Manager\HABundle\Model\Configuration;

class DefaultController extends Controller
{
	public function indexAction(){
		return $this->render('ManagerHABundle:Default:index.html.twig', array('manager' => ManagerMHA::getInstance()));
	}

	public function logAction(){
		return new Response(nl2br(shell_exec('tail -n 15 ' . ManagerMHA::getInstance()->getMha()->getLogPath())));
	}

	public function syncAction(){
		$response = shell_exec("/bin/bash " . Configuration::getInstance()->getScriptsPath(). "synchronize --ip_master_to_slave=" . ManagerMHA::getInstance()->getMha()->getSlaveBddIp() . " --ip_slave_to_master=" . ManagerMHA::getInstance()->getMha()->getMainBddIp());
		
		echo "/usr/bin/sudo /bin/bash " . Configuration::getInstance()->getScriptsPath(). "synchronize --ip_master_to_slave=" . ManagerMHA::getInstance()->getMha()->getMainBddIp() . " --ip_slave_to_master=" . ManagerMHA::getInstance()->getMha()->getSlaveBddIp();
		
		return new response($response);
	}

	public function start_mhaAction(){
		exec('sudo /etc/init.d/mha_daemon restart');
		return new response();
	}
}
