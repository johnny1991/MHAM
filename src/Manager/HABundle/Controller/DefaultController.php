<?php

namespace Manager\HABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$array1 = array('ip' => '172.20.0.225',
    					'status' => 'Master',
    					'log_binaire' => 'Maria-00097.bin',
    					'pos_binaire' => '548752',
    					'io_running' => ' ',
    					'sql_running' => ' ');
    	
    	$array2 = array('ip' => '172.20.0.236',
		    			'status' => 'Slave',
		    			'log_binaire' => 'Maria-00097.bin',
		    			'pos_binaire' => '548752',
    					'io_running' => 'Yes',
    					'sql_running' => 'Yes');
    	$array = array($array1, $array2);
    	
        return $this->render('ManagerHABundle:Default:index.html.twig', array('bdd' => $array));
    }
    
    public function logAction(){
		$html = nl2br(shell_exec('tail -n 15 /var/log/masterha/MHA.log'));
    	$response = new Response(json_encode($html));
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
    
}
