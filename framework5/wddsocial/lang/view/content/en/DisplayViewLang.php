<?php

/*
* WDD Social: Language Pack for 
*/

class DisplayViewLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			# general
			case 'edit':
				return 'Edit';
			
			case 'edit_title':
				return "Edit &ldquo;{$var}&rdquo;";
			
			case 'delete':
				return 'Delete';
			
			case 'delete_title':
				return "Delete &ldquo;{$var}&rdquo;";
			
			case 'flag':
				return 'Flag';
			
			case 'flag_title':
				return "Flag &ldquo;{$var}&rdquo;";
			
			case 'comments':
				if ($var == '1') return "{$var} comment";
				return "{$var} comments";
						
			case 'comments_title':
				return "{$var} | Comments";
			
			case 'edit_comment':
				return "Edit Comment on &ldquo;{$var}&rdquo;";
			
			case 'delete_comment':
				return "Delete Comment on &ldquo;{$var}&rdquo;";
			
			case 'flag_comment':
				return "Flag Comment on &ldquo;{$var}&rdquo;";
			
			case 'posted_a':
				return 'posted a';
			
			case 'wrote_an':
				return 'wrote an';
			
			case 'project':
				return 'project';
			
			case 'article':
				return 'article';
			
			case 'category_title':
				return "Categories | {$var}";
			
			case 'joined':
				return 'joined the community';
			
			case 'download_ical':
				return "Download {$var} iCal File";
			
			case 'ical':
				return 'iCal';
			
			case 'search_maps':
				return "Search Google Maps for {$var}";
			
			case 'see_all_jobs':
				return "See {$var} Job Postings";
		}
	}
}