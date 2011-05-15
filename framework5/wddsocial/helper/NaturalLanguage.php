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
		$lang = new \Framework5\Lang('wddsocial.lang.CommonLang');
		if (UserSession::is_current($id)) {
			return $lang->text('your');
		}
		else {
			return static::possessive($name);
		}
	}
	
	
	
	/**
	* Creates the view profile text for display
	*/
	
	public static function view_profile($id, $name) {
		$lang = new \Framework5\Lang('wddsocial.lang.CommonLang');
		if (UserSession::is_current($id)) {
			return $lang->text('view_own_profile');
		}
		else {
			return $lang->text('view_user_profile', static::possessive($name));
		}
	}
	
	
	
	/**
	* Creates the display name of a user
	*/
	
	public static function display_name($id, $name) {
		$lang = new \Framework5\Lang('wddsocial.lang.CommonLang');
		return (UserSession::is_current($id))?$lang->text('you'):$name;
	}
	
	
	
	/**
	* Creates a comma delimited list of strings
	*/
	
	public static function comma_list($strings){
		$lang = new \Framework5\Lang('wddsocial.lang.CommonLang');
		for($i = 0; $i < count($strings); $i++){
			if($i == count($strings)-1){
				$list .= "{$lang->text('and')} {$strings[$i]}";
			}else{
				$list .= "{$strings[$i]}";
				$list .= (count($strings) > 2)?', ':' ';
			}
		}
		return $list;
	}
}