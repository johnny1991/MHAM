<?php

namespace Manager\HABundle\Model;

class MagentoServer extends Server {

	public function __construct($ip, $user, $password){
		parent::__construct($ip, $user, $password);
	}

	/*public function update(){
		parent::update();
	}*/

}