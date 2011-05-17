<?php

/*
* IndexPage Language Pack - English
* 
* @author tmatthewsdev (tmatthewsdev@gmail.com)
*/

class ContactPageLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			
			# page info
			case 'page-title':
				return 'Contact';
			
		}		
	}
}