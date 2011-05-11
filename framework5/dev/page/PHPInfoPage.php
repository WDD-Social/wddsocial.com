<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class PHPInfoPage implements \Framework5\IExecutable {
	
	public function execute() {
		echo phpinfo();
	}
}