<?php

namespace Ajax;

/**
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class Test implements \Framework5\IExecutable {
	
	public function execute() {
		echo $_SERVER['HTTP_REFERER'];
	}
}