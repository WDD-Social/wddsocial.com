<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class Http404 implements \Framework5\IExecutable {

	public static function execute() {
		echo "{developer 404 page}<br/>";
	}
}