<?php

namespace WDDSocial;
/*
*
* @author: Anthony Colangelo (me@acolangelo.com)
*
*/
class NaturalLanguage{
	
	# CONVERTS A STRING INTO ITS POSSESSIVE FORM
	public static function possessive($string){
		if(substr($string, -1) == 's'){
			$string .= "&rsquo;";
		}else{
			$string .= "&rsquo;s";
		}
		return $string;
	}
	
	
	
	# CREATES THE VIEW PROFILE TEXT FOR DISPLAY
	public static function view_profile($id, $name){
		import('wddsocial.controller.UserValidator');
		$withPossessive = "View " . static::possessive($name) . " Profile";
		return (\WDDSocial\UserValidator::is_current($id))?"View Your Profile":$withPossessive;
	}
	
	
	
	# CREATES THE DISPLAY NAME OF A USER
	public static function display_name($id, $name){
		import('wddsocial.controller.UserValidator');
		return (\WDDSocial\UserValidator::is_current($id))?"You":$name;
	}
}