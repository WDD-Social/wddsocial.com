<?php

namespace WDDSocial;
/*
*
* @author: Anthony Colangelo (me@acolangelo.com)
*
*/
class NaturalLanguage{
	
	/**
	* Converts a string into its possessive form
	*/
	
	public static function possessive($string){
		if(substr($string, -1) == 's'){
			$string .= "&rsquo;";
		}else{
			$string .= "&rsquo;s";
		}
		return $string;
	}
	
	
	
	/**
	* Creates the view profile text for display
	*/
	
	public static function view_profile($id, $name){
		import('wddsocial.controller.UserValidator');
		$withPossessive = "View " . static::possessive($name) . " Profile";
		return (\WDDSocial\UserValidator::is_current($id))?"View Your Profile":$withPossessive;
	}
	
	
	
	/**
	* Creates the display name of a user
	*/
	
	public static function display_name($id, $name){
		import('wddsocial.controller.UserValidator');
		return (\WDDSocial\UserValidator::is_current($id))?"You":$name;
	}
}