<?php

namespace WDDSocial;

/*
* 
* 
* @author: Anthony Colangelo (me@acolangelo.com)
*/

class ArticlePage implements \Framework5\IExecutable {
	
	public static function execute() {	
		
		$article = static::getArticle(\Framework5\Request::segment(1));
			
		if($article == false){
			echo render(':template', 
				array('section' => 'top', 'title' => "Article Not Found"));
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
			echo "<h1>Article Not Found</h1>";
			echo render('wddsocial.view.WDDSocial\SectionView',
					array('section' => 'end_content'));
		}else{
			# display site header
			echo render(':template', 
				array('section' => 'top', 'title' => "{$article->title}"));
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
			
			# display Article overview
			static::displayArticleOverview($article);
			static::displayArticleAuthors($article);
			static::displayArticleMedia($article);
			static::displayArticleComments($article->comments);
			
			echo render('wddsocial.view.WDDSocial\SectionView',
					array('section' => 'end_content'));
			
		}
		
		echo render(':template', 
				array('section' => 'bottom'));
	}
	
	
	
	/**
	* Gets the requested Article and data
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
	
	
	
	/**
	* Gets the requested Article and data
	*/
	
	private static function displayArticleOverview($article){
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'Article', 'classes' => array('large', 'with-secondary'), 'header' => $article->title));
		echo render('wddsocial.view.WDDSocial\ContentView', array('section' => 'overview', 'content' => $article));
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'Article'));
	}
	
	
	
	/**
	* Gets the requested Article and data
	*/
	
	private static function displayArticleAuthors($article){
		$headerText = 'Author';
		if(count($article->team) > 1 || count($article->team) < 1){
			$headerTest .= 's';
		}
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'authors', 'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 'header' => $headerText));
		echo render('wddsocial.view.WDDSocial\ContentView', array('section' => 'members', 'content' => $article));
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'authors'));
	}
	
	
	
	/**
	* Gets the requested Article and data
	*/
	
	private static function displayArticleMedia($article){
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'media', 'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 'header' => 'Media', 'extra' => 'media_filters'));
		echo render('wddsocial.view.WDDSocial\ContentView', array('section' => 'media', 'content' => $article, 'active' => 'images'));
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'media'));
	}
	
	
	
	/**
	* Gets the requested Article and data
	*/
	
	private static function displayArticleComments($comments){
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'comments', 'classes' => array('medium', 'with-secondary'), 'header' => 'Comments'));
		echo render('wddsocial.view.WDDSocial\ContentView', array('section' => 'comments', 'comments' => $comments));
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'comments'));
	}
}