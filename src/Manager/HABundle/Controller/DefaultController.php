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

	public function syncAction($ip){
		$change_mha_conf = ($ip == ManagerMHA::getInstance()->getMha()->getMainBddIp()) ? false : true;
		$command = "/bin/bash " . Configuration::getInstance()->getScriptsPath(). "synchronize --master=" . $ip . "--change_mha_conf=" . $change_mha_conf;
		$response = shell_exec($command);
		echo $command;
		echo "\n";
		return new response($response);
	}

	public function start_mhaAction(){
		exec('sudo /etc/init.d/mha_daemon restart');
		return new response();
	}
}
