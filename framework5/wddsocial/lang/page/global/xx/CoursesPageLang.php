<?php

/*
* WDD Social: Language Pack for CoursesPage
* Language: xx
*/

class CoursesPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'page-title':
				return 'XX'; # Courses
			
			case 'page-header':
				return 'XX'; # Courses
			
			
			# content sorters
			case 'sort-month':
				return 'XX'; # month
			
			case 'sort-alphabetically':
				return 'XX'; # alphabetically
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}