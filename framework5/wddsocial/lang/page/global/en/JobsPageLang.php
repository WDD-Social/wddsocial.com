<?php

/*
* WDD Social: Language Pack for 
*/

class JobsPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'page-title':
				return 'Jobs';
			
			case 'sort-company':
				return 'company';
			
			case 'sort-location':
				return 'location';
			
			case 'sort-newest':
				return 'newest';
			
			case 'search_maps':
				return "Search Google Maps for {$var}";
			
			case 'see_all_jobs':
				return "See {$var} Job Postings";
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}