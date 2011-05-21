<?php

/*
* WDD Social: Language Pack for 
*/

class DisplayViewLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			# general
			case 'edit':
				return 'XX'; # Edit
			
			case 'edit_title':
				return "XX {$var}"; # Edit &ldquo;{$var}&rdquo;
			
			case 'delete':
				return 'XX'; # Delete
			
			case 'delete_title':
				return "XX {$var}"; # Delete &ldquo;{$var}&rdquo;
			
			case 'flag':
				return 'XX'; # Flag
			
			case 'flag_title':
				return "XX {$var}"; # Flag &ldquo;{$var}&rdquo;
						
			case 'posted_a':
				return 'XX'; # posted a
			
			case 'wrote_an':
				return 'XX'; # wrote an
			
			case 'project':
				return 'XX'; # project
			
			case 'article':
				return 'XX'; # article
			
			case 'category_title':
				return "XX | {$var}"; # Categories | {$var}
			
			case 'joined':
				return 'XX'; # joined the community
			
			case 'and':
				return 'XX'; # and
			
			case 'search_maps':
				return "XX {$var}"; # Search Google Maps for {$var}
			
			case 'see_all_jobs':
				return "XX {$var}"; # See {$var} Job Postings
			
			case 'description':
				return 'XX'; # Description
			
			case 'no_description':
				return 'XX'; # No description has been added.
			
			case 'completion_date':
				return "XX"; # Completed in {$var}.
			
			case 'posted_date':
				return "XX"; # Posted {$var}
			
			case 'written_date':
				return "XX"; # Written {$var}
			
			case 'categories':
				return 'XX'; # Categories
			
			case 'no_categories':
				return 'XX'; # No categories have been added.
			
			case 'links':
				return 'XX'; # Links
			
			case 'no_links':
				return 'XX'; # No links have been added.
			
			case 'apply_title':
				return 'XX'; # Apply for this job
			
			case 'apply_now':
				return 'XX'; # Apply Now
			
			
			# Members
			case 'edit_team':
				return "XX"; # Edit &ldquo;$var Team&rdquo;
			
			case 'edit_authors':
				return "XX"; # Edit &ldquo;$var Authors&rdquo;
			
			case 'edit_members':
				return "XX"; # Edit &ldquo;$var Members&rdquo;
			
			case 'no_members':
				return "XX"; # No one has been added. Well, that&rsquo;s pretty lonely.
			
			
			# Events
			case 'download_ical':
				return "XX {$var}"; # Download {$var} iCal File
			
			case 'ical':
				return 'XX'; # iCal
			
			case 'download_ical_file':
				return 'XX'; # Download iCal File
			
			case 'owner_edit_event':
				return "XX $var XX"; # Edit $var Location and Time
			
			
			# Comments
			case 'comments':
				if ($var == '1') return "{$var} XX"; # {$var} comment
				return "{$var} XX"; # {$var} comments
			
			case 'comments_title':
				return "{$var} | XX"; # {$var} | Comments
			
			case 'edit_comment':
				return ""; # Edit Comment on &ldquo;{$var}&rdquo;
			
			case 'delete_comment':
				return ""; # Delete Comment on &ldquo;{$var}&rdquo;
			
			case 'flag_comment':
				return "XX {$var}"; # Flag Comment on &ldquo;{$var}&rdquo;
			
			case 'edit_comment_title':
				return 'XX'; # Edit Your Comment
			
			case 'delete_comment_title':
				return 'XX'; # Delete Your Comment
			
			case 'flag_user_comment':
				return "XX {$var} XX"; # Flag {$var} Comment
			
			case 'no_comments':
				return 'XX'; # No one has commented yet, why don&rsquo;t you start the conversation?
			
			case 'signin_required':
				return 'XX'; # You must be signed in to add a comment.
			
			case 'signin_title':
				return 'XX'; # Sign In to WDD Social
			
			case 'signin_link':
				return 'XX'; # Would you like to sign in?
			
			
			# Media
			case 'images':
				return 'XX'; # Images
			
			case 'related-images';
				return 'XX'; # Related Images
			
			case 'videos':
				return 'XX'; # Videos
			
			case 'related-videos':
				return 'XX'; # Related Videos
			
			case 'no_images':
				return 'XX'; # Welp! No images have been added, so this page will look a little plain...
			
			case 'no_videos':
				return 'XX'; # Uh oh, no videos have been added.
			
			
			# Job
			case 'jobtype':
				switch ($var) {
					case 'Internship':
						return 'XX'; # Internship
					case 'Full-Time':
						return 'XX'; # Full-Time
					case 'Part-Time':
						return 'XX'; # Part-Time
					default:
						return $var;
				}
			
			case 'job_type_intern':
				return "an <strong><a href=\"/jobs\" title=\"{$var} Jobs\">{$var}</a></strong>";
			
			case 'job_type_gig':
				 return "a <strong><a href=\"/jobs\" title=\"{$var} Jobs\">{$var}</a></strong> gig";
			
			case 'edit_job_title':
				return "XX {$var['title']} XX {$var['company']} XX"; # Edit {$var['title']} at {$var['company']} Details
			
			case 'jobtype_display':
				return "XX {$var}"; # This job is {$var}.
			
			case 'compensation_display':
				return "XX {$var}"; # Compensation is <strong>{$var}</strong>
			
			default:
				throw new Exception("Invalid lang content $id");
		}
	}
}