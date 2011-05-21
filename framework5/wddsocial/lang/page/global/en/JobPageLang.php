<?php

/*
* WDD Social: Language Pack for 
*/

class JobPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'not-found-page-title':
				return 'Job Not Found';
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}