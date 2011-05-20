<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class Http404 implements \Framework5\IExecutable {

	public function execute() {
		echo render(':template', 
			array('title' => "404: page not found", 'content' => "developer 404 page"));
	}
}