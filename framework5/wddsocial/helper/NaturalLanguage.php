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
	* Returns either possessive name or 'Your'
	*/
	
	public static function ownership($id, $name){
		$possessive = static::possessive($name);
		return (UserSession::is_current($id))?"Your":$possessive;
	}
	
	
	
	/**
	* Creates the view profile text for display
	*/
	
	public static function view_profile($id, $name){
		$withPossessive = "View " . static::possessive($name) . " Profile";
		return (UserSession::is_current($id))?"View Your Profile":$withPossessive;
	}
	
	
	
	/**
	* Creates the display name of a user
	*/
	
	public static function display_name($id, $name){
		return (UserSession::is_current($id))?"You":$name;
	}
	
	
	
	/**
	* Creates a comma delimited list of strings
	*/
	
	public static function comma_list($strings){
		for($i = 0; $i < count($strings); $i++){
			if($i == count($strings)-1){
				$list .= "and {$strings[$i]}";
			}else{
				$list .= "{$strings[$i]}, ";
			}
		}
		return $list;
	}
}