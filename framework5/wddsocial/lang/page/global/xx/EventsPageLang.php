<?php

/*
* WDD Social: Language Pack for EventsPage
* Language: xx
*/

class EventsPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'page-title':
				return 'XX'; # Events
			
			case 'page-header':
				return 'XX'; # Events
			
			# content sorters
			case 'sort-upcoming':
				return 'XX'; # upcoming
			
			case 'sort-alphabetically':
				return 'XX'; # alphabetically
			
			case 'sort-newest':
				return 'XX'; # newest
			
			case 'sort-oldest':
				return 'XX'; # oldest
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}