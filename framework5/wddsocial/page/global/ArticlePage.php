<?php

namespace WDDSocial;

/*
* Article Page
* 
* @author: Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class ArticlePage implements \Framework5\IExecutable {
	
	public static function execute() {	
		
		# get article information
		$article = static::getArticle(\Framework5\Request::segment(1));
		
		# get page language pack
		$lang = new \Framework5\Lang('wddsocial.lang.page.global.ArticlePageLang');
		
		
		# if the article exists
		if ($article) {
			# display site header
			echo render(':template', array('section' => 'top', 'title' => $article->title));
			echo render(':section', array('section' => 'begin_content'));
			
			
			# display article overview
			echo render(':section', 
				array('section' => 'begin_content_section', 'id' => 'Article', 
					'classes' => array('large', 'with-secondary'), 'header' => $article->title));
			echo render('wddsocial.view.content.WDDSocial\OverviewDisplayView', $article);
			echo render(':section', array('section' => 'end_content_section', 'id' => 'Article'));
			
			
			# translate and natural language
			if (count($article->team) > 1 || count($article->team) < 1) $author_header = $lang->text('authors');
			else $author_header = $lang->text('author');
			
			# display article authors
			echo render(':section', 
				array('section' => 'begin_content_section', 'id' => 'authors', 
					'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 
					'header' => $author_header));
			echo render('wddsocial.view.content.WDDSocial\MembersDisplayView', $article);
			echo render(':section', array('section' => 'end_content_section', 'id' => 'authors'));
			
			
			# display article media
			echo render(':section', 
				array('section' => 'begin_content_section', 'id' => 'media', 
					'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 
					'header' => $lang->text('media'), 'extra' => 'media_filters'));
			echo render('wddsocial.view.content.WDDSocial\MediaDisplayView', 
				array('content' => $article, 'active' => 'images'));
			echo render(':section', array('section' => 'end_content_section', 'id' => 'media'));
			
			
			# display article comments
			echo render(':section', 
				array('section' => 'begin_content_section', 'id' => 'comments', 
					'classes' => array('medium', 'with-secondary'), 'header' => $lang->text('comments')));
			echo render('wddsocial.view.content.WDDSocial\CommentDisplayView', $article->comments);
			echo render(':section', array('section' => 'end_content_section', 'id' => 'comments'));
			
		}
		
		
		# the article does not exist
		else {
			# display site header
			echo render(':template', array('section' => 'top', 'title' => $lang->text('article_not_found')));
			echo render(':section', array('section' => 'begin_content'));
			echo "<h1>{$lang->text('article_not_found')}</h1>";
			
		}
		
		
		# display page footer
		echo render(':section', array('section' => 'end_content'));
		echo render(':template', array('section' => 'bottom'));
	}
	
	
	
	/**
	* get the requested article data
	*/
	
	private static function getArticle($vanityURL){
		import('wddsocial.model.WDDSocial\ContentVO');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$data = array('vanityURL' => $vanityURL);
		$query = $db->prepare($sql->getArticleByVanityURL);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ContentVO');
		$query->execute($data);
		return $query->fetch();
	}
}