<?php

/*
* ArticlePage Language Pack - English
* 
* @author tmatthewsdev (tmatthewsdev@gmail.com)
*/

class ArticlePageLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			# 
			case 'author':
				return 'Author';
			case 'authors':
				return 'Authors';
			
			case 'media':
				return 'Media';
			case'comments':
				return 'Comments';
			
			
			case 'article_not_found':
				return 'Article Not Found';
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}