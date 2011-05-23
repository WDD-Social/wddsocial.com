<?php

/*
* WDD Social: Language Pack for EventsPage
* Language: xx
*/

class EventsPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'page-title':
				return 'Events';
			
			case 'page-header':
				return 'Events';
			
			# content sorters
			case 'sort-upcoming':
				return 'upcoming';
			
			case 'sort-alphabetically':
				return 'alphabetically';
			
			case 'sort-newest':
				return 'newest';
			
			case 'sort-oldest':
				return 'oldest';
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}