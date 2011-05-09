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
						return 'Internship';
					case 'Full-Time':
						return 'Full-Time';
					case 'Part-Time':
						return 'Part-Time';
					default:
						throw new Exception('invalid language pack job type');
				}
			
			case 'job_type_intern':
				return "an <strong><a href=\"/jobs\" title=\"$var Jobs\">$var</a></strong>";
			case 'job_type_gig':
				 return "a <strong><a href=\"/jobs\" title=\"$var Jobs\">$var</a></strong> gig";
			
			
			case 'search_maps':
				return "Search Google Maps for $var";
			
			case 'edit_title':
				return "Edit {$var['title']} at {$var['company']} Details";
			case 'edit':
				return "Edit";
			
			case 'jobtype_display':
				return "This job is {$var}.";
			case 'compensation_display':
				return "Compensation is <strong>{$var}</strong>";
		}		
	}
}