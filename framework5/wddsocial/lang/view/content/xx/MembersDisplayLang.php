<?php

/*
* WDD Social: Language Pack for view.content.MembersDisplayView
*/

class MembersDisplayLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {		
			case 'edit':
				return 'XX'; # Edit
			
			case 'edit_team':
				return "XX $var"; # Edit &ldquo;$var Team&rdquo;
			
			case 'edit_authors':
				return "XX $var"; # Edit &ldquo;$var Authors&rdquo;
			
			case 'edit_members':
				return "XX $var"; # Edit &ldquo;$var Members&rdquo;
			
			case 'no_members':
				return "XX"; # No one has been added. Well, that&rsquo;s pretty lonely.
		}
	}
}