<?php

/*
* WDD Social: Language Pack for 
*/

class DisplayViewLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			# General
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
			
			case 'and':
				return 'and';
			
			case 'search_maps':
				return "Search Google Maps for {$var}";
			
			case 'see_all_jobs':
				return "See {$var} Job Postings";
			
			case 'description':
				return 'Description';
			
			case 'no_description':
				return 'No description has been added.';
			
			case 'completion_date':
				return "Completed in {$var}.";
			
			case 'posted_date':
				return "Posted {$var}";
			
			case 'written_date':
				return "Written {$var}";
			
			case 'categories':
				return 'Categories';
			
			case 'no_categories':
				return 'No categories have been added.';
			
			case 'links':
				return 'Links';
			
			case 'no_links':
				return 'No links have been added.';
			
			case 'apply_title':
				return 'Apply for this job';
			
			case 'apply_now':
				return 'Apply Now';
			
			
			# Members
			case 'edit_team':
				return "Edit &ldquo;$var Team&rdquo;";
			
			case 'edit_authors':
				return "Edit &ldquo;$var Authors&rdquo;";
			
			case 'edit_members':
				return "Edit &ldquo;$var Members&rdquo;";
			
			case 'no_members':
				return "No one has been added. Well, that&rsquo;s pretty lonely.";
			
			
			# Events
			case 'download_ical':
				return "Download {$var} iCal File";
			
			case 'ical':
				return 'iCal';
			
			case 'download_ical_file':
				return 'Download iCal File';
			
			case 'owner_edit_event':
				return "Edit $var Location and Time";
			
			
			# Comments
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
			
			case 'edit_comment_title':
				return 'Edit Your Comment';
			
			case 'delete_comment_title':
				return 'Delete Your Comment';
			
			case 'flag_user_comment':
				return "Flag {$var} Comment";
			
			case 'no_comments':
				return 'No one has commented yet, why don&rsquo;t you start the conversation?';
			
			case 'signin_required':
				return 'You must be signed in to add a comment.';
			
			case 'signin_title':
				return 'Sign In to WDD Social';
			
			case 'signin_link':
				return 'Would you like to sign in?';
			
			
			# Media
			case 'images':
				return 'Images';
			
			case 'related-images';
				return 'Related Images';
			
			case 'videos':
				return 'Videos';
			
			case 'related-videos':
				return 'Related Videos';
			
			case 'no_images':
				return 'Welp! No images have been added, so this page will look a little plain...';
			
			case 'no_videos':
				return 'Uh oh, no videos have been added.';
			
			
			
			
			# Job
			case 'jobtype':
				switch ($var) {
					case 'Internship':
						return 'Internship';
					case 'Full-Time':
						return 'Full-Time';
					case 'Part-Time':
						return 'Part-Time';
					default:
						return $var;
				}
			
			case 'job_type_intern':
				return "an <strong><a href=\"/jobs\" title=\"{$var} Jobs\">{$var}</a></strong>";
			
			case 'job_type_gig':
				 return "a <strong><a href=\"/jobs\" title=\"{$var} Jobs\">{$var}</a></strong> gig";
			
			case 'edit_job_title':
				return "Edit {$var['title']} at {$var['company']} Details";
			
			case 'jobtype_display':
				return "This job is {$var}.";
			
			case 'compensation_display':
				return "Compensation is <strong>{$var}</strong>";
			
			default:
				throw new Exception("Invalid lang content $id");
		}
	}
}