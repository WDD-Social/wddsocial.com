<?php

/*
* WDD Social: Language Pack for 
*/

class PeoplePageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'page-title':
				return 'People';
			
			case 'page-header':
				return 'People';
			
			case 'sort-alphabetically':
				return 'alphabetically';
			
			case 'sort-oldest':
				return 'oldest';
			
			case 'sort-newest':
				return 'newest';
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}