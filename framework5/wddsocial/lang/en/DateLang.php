<?php

/*
* Date language pack
* English
*/

class DateLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		
		
		# define language 
		$months = array('January', 'February', 'March', 'April', 'May', 'June', 
			'July', 'August', 'September', 'October', 'November', 'December', );
		
		
		
		
		# get lower case string to search against 
		$search = strtolower($id);
		
		# search abbreviations
		$key_abbr = array('jan', 'feb', 'mar', 'apr', 'may', 'jun', 
			'jul', 'aug', 'sep', 'oct', 'nov', 'dec', );
		$key = array_search($search, $key_abbr);
		
		if ($key !== false) {
			return $months[$key];
		}
		
		# search full month names
		$key_full = array('january', 'february', 'march', 'april', 'may', 'june', 
			'july', 'august', 'september', 'october', 'november', 'december', );
		$key = array_search($search, $key_full);
		if ($key !== false) {
			return $months[$key];
		}
		
		# search days of the week
		throw new Exception("Invalid value '$id'");
	}
}