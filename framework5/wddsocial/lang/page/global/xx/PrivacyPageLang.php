<?php

/*
* WDD Social: Language Pack for 
*/

class PrivacyPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'page-title':
				return 'Privacy Policy';
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}