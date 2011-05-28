<?php

namespace WDDSocial;

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
	* 	formats links into anchor tags
	* 	formats @ addresses to wddsocial username, or twitter
	*/
	
	public static function format_user_content($text) {
		return $text;
	}
}