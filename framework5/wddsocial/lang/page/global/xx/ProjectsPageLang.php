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
			
			case 'sorter-':
				return 'alphabetically';
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}