<?php

/*
* WDD Social: Language Pack for view.SectionView
*/

class SectionViewLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			
			# end_content_section()
			
			case 'load_more':
				return 'XX'; # Load More
			
			# general
			case 'all':
				return 'XX'; # All
			
			case 'people':
				return 'XX'; # People
			
			case 'projects':
				return 'XX'; # Projects
			
			case 'articles':
				return 'XX'; # Articles
			
			# latest_filters
			case 'filter_all_title':
				return 'XX'; # All Latest Activity
			
			case 'filter_people_title':
				return 'XX'; # Latest People
			
			case 'filter_projects_title':
				return 'XX'; # Latest Projects
			
			case 'filter_articles_title':
				return 'XX'; # Latest Articles
			
			# user_latest_filters
			case 'all_latest_activity':
				return 'XX'; # All Latest Activity
			
			case 'latest_projects':
				return 'XX'; # Latest Projects
			
			case 'latest_articles':
				return 'XX'; # Latest Articles
			
			# slider_controls
			case 'related_images':
				return 'XX'; # Related Images
			
			case 'images':
				return 'XX'; # Images
			
			case 'related_videos':
				return 'XX'; # Related Videos
			
			case 'videos':
				return 'XX'; # Videos
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}		
	}
}