<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class Formatter extends \Framework5\StaticController {
	
	public function format_time($timestamp) {
		return date("F j, Y, g:i a", $timestamp);
	}
}
