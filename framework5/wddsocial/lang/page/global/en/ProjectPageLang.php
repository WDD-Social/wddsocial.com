<?php

/*
* WDD Social: Language Pack for 
*/

class ProjectPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'not-found-page-title':
				return 'Project Not Found';
		}
	}
}