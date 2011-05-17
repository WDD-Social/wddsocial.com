<?php

/*
* WDD Social: Language Pack for 
*/

class Http404Lang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'page-title':
				return '404: Page not found';
		}
	}
}