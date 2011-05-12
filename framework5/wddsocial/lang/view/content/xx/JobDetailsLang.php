<?php

/*
* WDD Social: Language Pack for view.content.EventLocationDisplayView
*/

class JobDetailsLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			
			case 'jobtype':
				switch ($var) {
					case 'Internship':
						return 'XX'; # Internship
					case 'Full-Time':
						return 'XX'; # Full-Time
					case 'Part-Time':
						return 'XX'; # Part-Time
					default:
						throw new Exception('invalid language pack job type');
				}
			
			case 'job_type_intern':
				return "an <strong><a href=\"/jobs\" title=\"$var Jobs\">$var</a></strong>";
			
			case 'job_type_gig':
				 return "a <strong><a href=\"/jobs\" title=\"$var Jobs\">$var</a></strong> gig";
			
			case 'search_maps':
				return "XX $var"; # Search Google Maps for $var
			
			case 'edit_title':
				return "XX {$var['title']} XX {$var['company']} XX"; # Edit {$var['title']} at {$var['company']} Details
			case 'edit':
				return "XX"; # Edit
			
			case 'jobtype_display':
				return "XX $var"; # This job is {$var}.
			
			case 'compensation_display':
				return "XX $var"; # Compensation is <strong>{$var}</strong>
		}		
	}
}