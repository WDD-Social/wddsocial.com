<?php

/*
* Profile Language Pack
* French
*/

class ProfileLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		
		switch ($id) {
			case 'intro':
				# get the month in the users language
				$var['month'] = text("DateLang:{$var['month']}");
				
				return "{$var['name']} est un &eacute;tudiant de {$var['age']} ans originaire du {$var['location']} qui a commen&ccedil;&eacute; &agrave; Full Sail en {$var['month']} {$var['year']}";
			
			
			case '':
				return "";
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}