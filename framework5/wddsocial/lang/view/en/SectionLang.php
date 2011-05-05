<?php

/*
* WDD Social: Language Pack for view.SectionView
*/

class SectionLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			
			# end_content_section()
			
			case 'load_more':
				return 'Load More';
			
			
			
			#
			# get_extra()
			#
			
			# general
			case 'all':
				return 'All';
			case 'people':
				return 'People';
			case 'projects':
				return 'Projects';
			case 'articles':
				return 'Articles';
			
			# latest_filters
			case 'filter_all_title':
				return 'All Latest Activity';
			case 'filter_people_title':
				return 'Latest People';
			case 'filter_projects_title':
				return 'Latest Projects';
			case 'filter_articles_title':
				return 'Latest Articles';
			
			# user_latest_filters
			case 'all_latest_activity':
				return 'All Latest Activity';
			case 'latest_projects':
				return 'Latest Projects';
			case 'latest_articles':
				return 'Latest Articles';
			
			# slider_controls
			case 'related_images':
				return 'Related Images';
			case 'images':
				return 'Images';
			case 'related_videos':
				return 'Related Videos';
			case 'videos':
				return 'Videos';
		}		
	}
}