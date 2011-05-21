<?php

/*
* IndexPage Language Pack - XX
* 
* @author tmatthewsdev (tmatthewsdev@gmail.com)
*/

class AboutPageLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			
			# page info
			case 'page-title':
				return 'XX'; # About
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}		
	}
}