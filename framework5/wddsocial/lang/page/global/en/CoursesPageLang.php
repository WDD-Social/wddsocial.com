<?php

/*
* WDD Social: Language Pack for 
*/

class CoursesPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'page-title':
				return 'Courses';
		}
	}
}