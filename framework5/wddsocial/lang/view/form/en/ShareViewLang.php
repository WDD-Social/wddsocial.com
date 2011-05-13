<?php

/*
* WDD Social: Language Pack for 
*/

class ShareViewLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'title':
				return 'Title';
			
			case 'type':
				return 'Type';
			
			case 'project':
				return 'Project';
			
			case 'article':
				return 'Article';
			
			case 'event':
				return 'Event';
			
			case 'job':
				return 'Job';
			
			case 'create':
				return 'Create';
			
		}
	}
}