<?php

/*
* WDD Social: Language Pack for view.content.CommentDisplayView
*/

class EventLocationLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'owner_edit_title':
				return "XX $var XX"; # Edit $var Location and Time
			
			case 'edit':
				return 'XX'; # Edit
			
			case 'download_ical_title':
				return "XX $var XX"; # Download $var iCal File
			
			case 'download_ical_file':
				return 'XX'; # Download iCal File
		}		
	}
}