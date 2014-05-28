<?php

namespace Manager\HABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Manager\HABundle\Model\ManagerMHA;
use Manager\HABundle\Model\Configuration;

class DefaultController extends Controller{
	
	public function indexAction(){
		return $this->render('ManagerHABundle:Default:index.html.twig', array('manager' => ManagerMHA::getInstance()));
	}

	public function logAction(){
		$start = microtime(true);
		//$command = nl2br(shell_exec('tail -n 7 ' . ManagerMHA::getInstance()->getMha()->getLogPath()));
		$command = shell_exec('tail -n 7 ' . ManagerMHA::getInstance()->getMha()->getLogPath());
		
		$time_taken = microtime(true) - $start;
		echo "dddddddddddddddddddd ". $time_taken . " zzzzzzzzzzzzzzzzzzzzzzz";
		return new Response($command);
	}

	public function syncAction($ip){
		$change_mha_conf = ($ip == ManagerMHA::getInstance()->getMha()->getMainBddIp()) ? "false" : "true";
		$command = "/bin/bash " . Configuration::getInstance()->getScriptsPath(). "synchronize --master=" . $ip . " --change_mha_conf=" . $change_mha_conf;
		$response = shell_exec($command);
		return new response($response);
	}
	
	public function change_public_ipAction(){
		$command = "/bin/bash " . Configuration::getInstance()->getScriptsPath(). "changePublicIp";
		$response = shell_exec($command);
		echo $command;
		return new response(shell_exec($command));
	}
	
	public function remove_public_ipAction($ip){
		$command = "/bin/bash " . Configuration::getInstance()->getScriptsPath(). "removePublicIp --ip=" . $ip;
		$response = shell_exec($command);
		return new response(shell_exec($command));
	}

	public function start_mhaAction(){
		exec('sudo /etc/init.d/mha_daemon restart');
		return new response();
	}
}
