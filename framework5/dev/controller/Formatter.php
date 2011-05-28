<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class Formatter extends \Framework5\StaticController {
	
	public function date_format($timestamp) {
		return date("F j, Y, g:i a", $timestamp);
	}
}
