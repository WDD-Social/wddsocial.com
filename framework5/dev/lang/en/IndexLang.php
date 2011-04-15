<?php

/*
* Sample language pack, English
*/

class IndexLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		
		switch ($id) {
			case 'welcome':
				return "hello, {$var['name']}!";
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}