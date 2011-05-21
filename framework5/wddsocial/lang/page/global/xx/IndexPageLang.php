<?php

/*
* WDD Social IndexPage Language Pack - XX
* 
* @author tmatthewsdev (tmatthewsdev@gmail.com)
*/

class IndexPageLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			
			# page info
			case 'page-title':
				return 'XX'; # Connecting the Full Sail University Web Community
			
			# headers
			case 'projects-header':
				return 'XX'; # Projects
			
			case 'signin-header':
				return 'XX'; # Sign In
			
			case 'people-header':
				return 'XX'; # People
			
			case 'articles-header':
				return 'XX'; # Articles
			
			case 'events-header':
				return 'XX'; # Events
			
			case 'share-header':
				return 'XX'; # Share
			
			case 'jobs-header':
				return 'XX'; # Latest
			
			case 'latest-header':
				return 'XX'; # Latest
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}		
	}
}