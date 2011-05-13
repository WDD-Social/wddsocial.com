<?php

/*
* WDD Social: Language Pack for view.content.MembersDisplayView
*/

class OverviewDisplayLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			case 'edit':
				return 'XX'; # Edit
			
			case 'delete':
				return 'XX'; # Delete
			
			case 'flag':
				return 'XX'; # Flag
			
			case 'edit_title':
				return "XX $var"; # Edit &ldquo;$var&rdquo;
			
			case 'delete_title':
				return "XX $var"; # Delete &ldquo;$var&rdquo;
			
			case 'flag_title':
				return "XX $var"; # Flag &ldquo;$var&rdquo;
			
			case 'description':
				return 'XX'; # Description
			
			case 'no_description':
				return 'XX'; # No description has been added. Lame.
			
			case 'completion_date':
				return "XX $var"; # Completed in $var.
			
			case 'posted_date':
				return "XX $var"; # Posted $var
			
			case 'written_date':
				return "XX $var"; # Written $var
			
			case 'categories':
				return 'XX'; # Categories
			
			case 'no_categories':
				return 'XX'; # No categories have been added. Such a shame...
			
			case 'links':
				return 'XX'; # Links
			
			case 'no_links':
				return 'XX'; # No links have been added. That&rsquo;s no fun.
			
			case 'apply_title':
				return 'XX'; # Apply for this job
			
			case 'apply_now':
				return 'XX'; # Apply Now
		}
	}
}