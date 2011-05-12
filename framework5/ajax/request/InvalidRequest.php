<?php

namespace Ajax;

/**
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class InvalidRequest implements \Framework5\IExecutable {
	
	public function execute() {
		echo json_encode('bad_request');
	}
}