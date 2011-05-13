<?php

/*
* ArticlePage Language Pack - XX
* 
* @author tmatthewsdev (tmatthewsdev@gmail.com)
*/

class ArticlePageLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			# 
			case 'author':
				return 'XX'; # Author
			
			case 'authors':
				return 'XX'; # Authors
			
			case 'media':
				return 'XX'; # Media
			
			case'comments':
				return 'XX'; # Comments
			
			case 'article_not_found':
				return 'XX'; # Article Not Found
			
		}
	}
}