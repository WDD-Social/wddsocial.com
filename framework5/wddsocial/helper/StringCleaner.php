<?php

namespace WDDSocial;
/*
*
* @author: Anthony Colangelo (me@acolangelo.com)
*
*/
class StringCleaner{
	
	/**
	* Cleans (an array of) characters out of a string
	*/
	
	public static function clean_characters($string,$characters){
		foreach($characters as $character){
			$string = str_replace($character,'',$string);
		}
		return $string;
	}
	
	
	
	/**
	* Clean links, usernames, etc
	*/
	
	public static function clean_link($string){
		return str_replace(array('http://','https://','www.'),'',$string);
	}
	
	public static function clean_twitter($string){
		$string = static::clean_link($string);
		return str_replace(array('twitter.com/#!/','twitter.com/','@'),'',$string);
	}
	
	public static function clean_facebook($string){
		$string = static::clean_link($string);
		return str_replace(array('facebook.com/'),'',$string);
	}
	
	public static function clean_github($string){
		$string = static::clean_link($string);
		return str_replace(array('github.com/'),'',$string);
	}
	
	public static function clean_dribbble($string){
		$string = static::clean_link($string);
		return str_replace(array('dribbble.com/'),'',$string);
	}
	
	public static function clean_forrst($string){
		$string = static::clean_link($string);
		return str_replace(array('forrst.com/','forrst.com/people/','people/'),'',$string);
	}
}