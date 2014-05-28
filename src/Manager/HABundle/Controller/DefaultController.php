<?php

namespace Manager\HABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Manager\HABundle\Model\ManagerMHA;
use Manager\HABundle\Model\MHA;
use Manager\HABundle\Model\Configuration;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller{
	
	public function indexAction(){
		$manager = ManagerMHA::getInstance();
		return $this->render('ManagerHABundle:Default:index.html.twig', array('manager' => $manager));
	}

	public function logAction(){
		$session = new Session();
		$session->start();
		
		$command = nl2br(shell_exec('tail -n 15 ' . $session->get('log_path')));
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

	public function restart_mhaAction(){
		MHA::restart();
		return new response();
	}
	
	public function start_mhaAction(){
		MHA::start();
		return new response();
	}
	
	public function stop_mhaAction(){
		MHA::stop();
		return new response();
	}
}
