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
		$html = nl2br(shell_exec('tail -n 15 ' . ManagerMHA::getInstance()->getMha()->getLogPath()));
		$response = new Response(json_encode($html));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

	public function syncAction(){
		$conf = ManagerMHA::getConf();
		$hostname = $conf['server1']['hostname'];
		
		/*if ($hostname == '172.20.0.225'){
			echo exec('/usr/bin/sudo /bin/bash /home/HA/auto-reverse');
		} elseif ($hostname == '172.20.0.236'){
			echo exec('/usr/bin/sudo /bin/bash /home/HA/auto-rereverse');
		}*/
		
		return new response('ok');
	}

	public function start_mhaAction(){
		exec('sudo /etc/init.d/mha_daemon restart');
		return new response();
	}
}
