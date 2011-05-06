<?php

/*
* IndexPage Language Pack - English
* 
* @author tmatthewsdev (tmatthewsdev@gmail.com)
*/

class IndexPageLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			# 
			case 'page_title':
				return 'Connecting the Full Sail University Web Community';
			
			# headers
			case 'projects_header':
				return 'Projects';
			case 'signin_header':
				return 'Sign In';
			case 'people_header':
				return 'People';
			case 'articles_header':
				return 'Articles';
			case 'events_header':
				return 'Events';
			
			case 'share_header':
				return 'Share';
			case 'jobs_header':
				return 'Jobs';
			case 'latest_header':
				return 'Latest';
			
			case '':
				return '';
			case '':
				return '';
			case '':
				return '';
			case '':
				return '';
			case '':
				return '';
			case '':
				return '';
			case '':
				return '';
			case '':
				return '';
			case '':
				return '';
			case '':
				return '';
			case '':
				return '';
		}		
	}
}