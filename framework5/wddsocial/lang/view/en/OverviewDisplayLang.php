<?php

/*
* WDD Social: Language Pack for view.content.MembersDisplayView
*/

class OverviewDisplayLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			case 'edit':
				return 'Edit';
			case 'delete':
				return 'Delete';
			case 'flag':
				return 'Flag';
				
			case 'edit_title':
				return "Edit &ldquo;$var&rdquo;";
			case 'delete_title':
				return "Delete &ldquo;$var&rdquo;";
			case 'flag_title':
				return "Flag &ldquo;$var&rdquo;";
			
			
			case 'description':
				return 'Description';
			case 'no_description':
				return 'No description has been added. Lame.';
			
			case 'completion_date':
				return "Completed in $var.";
			case 'posted_date':
				return "Posted $var";
			case 'written_date':
				return "Written $var";
			
			case 'categories':
				return 'Categories';
			case 'no_categories':
				return 'No categories have been added. Such a shame...';
			
			case 'links':
				return 'Links';
			case 'no_links':
				return 'No links have been added. That&rsquo;s no fun.';
			
			case 'apply_title':
				return 'Apply for this job';
			case 'apply_now':
				return 'Apply Now';
		}
	}
}