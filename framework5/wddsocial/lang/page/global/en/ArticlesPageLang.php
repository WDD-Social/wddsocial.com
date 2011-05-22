<?php

/*
* WDD Social: Language Pack for 
*/

class ArticlesPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			# page info
			case 'page-title':
				return 'Articles';
			
			case 'page-header':
				return 'Articles';
			
			# content sorters
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