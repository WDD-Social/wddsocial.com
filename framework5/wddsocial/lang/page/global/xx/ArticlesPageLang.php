<?php

/*
* WDD Social: Language Pack for 
*/

class ArticlesPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			# page info
			case 'page-title':
				return 'XX'; # Articles
			
			case 'page-header':
				return 'XX'; # Articles
			
			# content sorters
			case 'sort-alphabetically':
				return 'XX'; # alphabetically
			
			case 'sort-oldest':
				return 'XX'; # oldest
			
			case 'sort-newest':
				return 'XX'; # newest
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}