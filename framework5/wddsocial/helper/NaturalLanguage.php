<?php

namespace WDDSocial;
/*
*
* @author: Anthony Colangelo (me@acolangelo.com)
*
*/
class NaturalLanguage{
	public static function possessive($string){
		if(substr($string, -1) == 's'){
			$string .= "&rsquo;";
		}else{
			$string .= "&rsquo;s";
		}
		return $string;
	}
	
	public static function view_profile($id, $name){
		import('wddsocial.controller.UserValidator');
		$withPossessive = "View ";
		$withPossessive .= static::possessive($name);
		$withPossessive .= " Profile";
		return (\WDDSocial\UserValidator::is_current($id))?"View Your Profile":$withPossessive;
	}
	
	public static function display_name($id, $name){
		import('wddsocial.controller.UserValidator');
		return (\WDDSocial\UserValidator::is_current($id))?"You":$name;
	}
}