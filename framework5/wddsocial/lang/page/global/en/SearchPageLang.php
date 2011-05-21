<?php

/*
* WDD Social: Language Pack for 
*/

class SearchPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'page-title':
				return 'Search';
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}