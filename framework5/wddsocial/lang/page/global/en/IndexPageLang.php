<?php

/*
* IndexPage Language Pack - English
* 
* @author tmatthewsdev (tmatthewsdev@gmail.com)
*/

class IndexPageLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			
			# page info
			case 'page-title':
				return 'Connecting the Full Sail University Web Community';
			
			# headers
			case 'projects-header':
				return 'Projects';
			
			case 'signin-header':
				return 'Sign In';
			
			case 'people-header':
				return 'People';
			
			case 'articles-header':
				return 'Articles';
			
			case 'events-header':
				return 'Events';
			
			case 'share-header':
				return 'Share';
			
			case 'jobs-header':
				return 'Jobs';
			
			case 'latest-header':
				return 'Latest';
		}		
	}
}