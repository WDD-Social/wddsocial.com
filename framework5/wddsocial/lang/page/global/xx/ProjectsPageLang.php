<?php

/*
* WDD Social: Language Pack for 
*/

class ProjectsPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'page-title':
				return 'XX'; # Projects
			
			case 'projects-header':
				return 'XX'; # Projects
			
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