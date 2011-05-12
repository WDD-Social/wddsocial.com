<?php

/*
* WDD Social IndexPage Language Pack - XX
* 
* @author tmatthewsdev (tmatthewsdev@gmail.com)
*/

class IndexPageLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			# 
			case 'page_title':
				return 'XX'; # Connecting the Full Sail University Web Community
			
			# headers
			case 'projects_header':
				return 'XX'; # Projects
				
			case 'signin_header':
				return 'XX'; # Sign In
				
			case 'people_header':
				return 'XX'; # People
				
			case 'articles_header':
				return 'XX'; # Articles
				
			case 'events_header':
				return 'XX'; # Events
			
			case 'share_header':
				return 'XX'; # Share
				
			case 'jobs_header':
				return 'XX'; # Jobs
				
			case 'latest_header':
				return 'XX'; # Latest
		}		
	}
}