<?php

namespace Framework5\Dev;

/*
* 
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class Formatter extends \Framework5\StaticController {
	
	/**
	* Determines what type of content to render
	*/
	
	public static function format_time($timestamp) {
		return date("F j, Y, g:i a", $timestamp);
	}
}