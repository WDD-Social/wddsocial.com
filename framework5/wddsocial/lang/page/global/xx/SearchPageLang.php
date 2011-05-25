<?php

/*
* WDD Social: Language Pack for 
*/

class SearchPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'page-title':
				return 'Search';
			
			case 'sort-alphabetically':
				return 'alphabetically';
			
			case 'sort-oldest':
				return 'oldest';
			
			case 'sort-newest':
				return 'newest';
			
			case 'sort-upcoming':
				return 'upcoming';
			
			case 'sort-company':
				return 'company';
			
			case 'sort-location':
				return 'location';
			
			case 'sort-month':
				return 'month';
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}