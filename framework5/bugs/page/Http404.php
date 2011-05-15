<?php

namespace Framework5\Bugs;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class Http404 implements \Framework5\IExecutable {

	public function execute() {
		echo "404 page<br/>";
	}
}