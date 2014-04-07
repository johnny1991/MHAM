<?php

namespace Manager\HABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Manager\HABundle\Model\ManagerMHA;

class DefaultController extends Controller
{
	public function indexAction(){
		return $this->render('ManagerHABundle:Default:index.html.twig', array('manager' => ManagerMHA::getInstance()));
	}

	public function logAction(){
		return new Response(nl2br(shell_exec('tail -n 15 ' . ManagerMHA::getInstance()->getMha()->getLogPath())));
	}

	public function syncAction(){
		$manager = ManagerMHA::getInstance();
		$response = shell_exec("/usr/bin/sudo /bin/bash { $manager->getConfiguration()->getScriptsPath() }synchronize --ip_master_to_slave={ $manager->getMha()->getSlaveBddIp() } --ip_slave_to_master={ $manager->getMha()->getMainBddIp() }");
		return new response($response);
	}

	public function start_mhaAction(){
		exec('sudo /etc/init.d/mha_daemon restart');
		return new response();
	}
}
