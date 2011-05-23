<?php

/*
* WDD Social: Language Pack for JobsPage
* Language: xx
*/

class JobsPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'page-title':
				return 'XX'; # Jobs
			
			
			# content filters
			case 'filter-all':
				return 'XX'; # all
			
			case 'filter-full-time':
				return 'XX'; # full-time
			
			case 'filter-part-time':
				return 'XX'; # part-time
			
			case 'filter-contract':
				return 'XX'; # contract
			
			case 'filter-freelance':
				return 'XX'; # freelance
			
			case 'filter-internship':
				return 'XX'; # internship
			
			
			# content sorters
			case 'sort-company':
				return 'XX'; # company
			
			case 'sort-location':
				return 'XX'; # location
			
			case 'sort-newest':
				return 'XX'; # newest
			
			case 'search_maps':
				return "XX {$var}"; # Search Google Maps for {$var}
			
			case 'see_all_jobs':
				return "XX {$var} XX"; # See {$var} Job Postings
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}