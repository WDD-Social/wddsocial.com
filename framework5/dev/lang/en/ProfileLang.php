<?php

/*
* Profile Language Pack
* English
*/

class ProfileLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		
		switch ($id) {
			case 'intro':
				# get the month in the users language
				$var['month'] = text("DateLang:{$var['month']}");
				
				return "{$var['name']} is a {$var['age']}-year-old, on-campus student from {$var['location']} who began Full Sail in {$var['month']} {$var['year']}";
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}