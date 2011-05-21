<?php

/*
* IndexPage Language Pack - English
* 
* @author tmatthewsdev (tmatthewsdev@gmail.com)
*/

class AboutPageLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			
			# page info
			case 'page-title':
				return 'About';
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}		
	}
}