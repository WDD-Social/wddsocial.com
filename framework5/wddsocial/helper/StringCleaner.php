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
}