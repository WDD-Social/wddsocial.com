<?php

namespace Framework5\Dev;

/*
* Content Formatter
* 
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class Formatter extends \Framework5\StaticController {
	
	/**
	* Format a timestamp into a readable date
	*/
	
	public static function format_timestamp($timestamp) {
		return date("F j, Y, g:i a", $timestamp);
	}
	
	
	
	/**
	* Formats user input
	* Changes links into anchor tags
	*/
	
	public static function format_user_content($text) {
		return $text;
	}
}