<?php

/*
* WDD Social: Language Pack for 
*/

class ProjectsPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'page-title':
				return 'Projects';
			
			case 'projects-header':
				return 'Projects';
			
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