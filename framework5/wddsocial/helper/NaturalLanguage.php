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
	
	public static function viewProfile($id, $name){
		import('wddsocial.helper.Validator');
		$withPossessive = "View ";
		$withPossessive .= static::possessive($name);
		$withPossessive .= " Profile";
		return (\WDDSocial\Validator::isCurrentUser($id))?"View Your Profile":$withPossessive;
	}
	
	public static function displayName($id, $name){
		import('wddsocial.helper.Validator');
		return (\WDDSocial\Validator::isCurrentUser($id))?"You":$name;
	}
}