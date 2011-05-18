<?php

namespace WDDSocial;

/*
* Article Page
* 
* @author: Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class ArticlePage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# get page language pack
		$lang = new \Framework5\Lang('wddsocial.lang.page.global.ArticlePageLang');
		
		# get article information
		$article = $this->getArticle(\Framework5\Request::segment(1));
		
		
		# handle form submission
		if (isset($_POST['submit'])){
			$response = $this->_process_form($article->id,$article->type);
			
			if ($response->status) {
				$article = null;
				$article = $this->getArticle(\Framework5\Request::segment(1));
			}
		}
		
		
		if ($article) {
			
			# page title
			$page_title = $article->title;
			
			
			$content = render(':section', array('section' => 'begin_content'));
			
			# display article overview
			$content.= render(':section', 
				array('section' => 'begin_content_section', 'id' => 'Article', 
					'classes' => array('large', 'with-secondary'), 'header' => $article->title));
			$content.= render('wddsocial.view.content.WDDSocial\OverviewDisplayView', $article);
			$content.= render(':section', array('section' => 'end_content_section', 'id' => 'Article'));
			
			
			# translate and natural language
			if (count($article->team) > 1 || count($article->team) < 1)
				$author_header = $lang->text('authors');
			else $author_header = $lang->text('author');
			
			# display article authors
			$content.= render(':section', 
				array('section' => 'begin_content_section', 'id' => 'authors', 
					'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 
					'header' => $author_header));
			$content.= render('wddsocial.view.content.WDDSocial\MembersDisplayView', $article);
			$content.= render(':section', array('section' => 'end_content_section', 'id' => 'authors'));
			
			
			# display article media
			$content.= render(':section', 
				array('section' => 'begin_content_section', 'id' => 'media', 
					'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 
					'header' => $lang->text('media'), 'extra' => 'media_filters'));
			$content.= render('wddsocial.view.content.WDDSocial\MediaDisplayView', 
				array('content' => $article, 'active' => 'images'));
			$content.= render(':section', array('section' => 'end_content_section', 'id' => 'media'));
			
			
			# display article comments
			$content.= render(':section', 
				array('section' => 'begin_content_section', 'id' => 'comments', 
					'classes' => array('medium', 'with-secondary'), 'header' => $lang->text('comments')));
			$content.= render('wddsocial.view.content.WDDSocial\CommentDisplayView', $article->comments);
			$content.= render(':section', array('section' => 'end_content_section', 'id' => 'comments'));
			
		}
		
		else {
			# display site header
			$page_title = $lang->text('article_not_found');
			$content = render(':section', array('section' => 'begin_content'));
			$content.= "<h1>{$lang->text('article_not_found')}</h1>";
			$content.= render(':section', array('section' => 'end_content'));
		}
		
		
		# display page
		echo render('wddsocial.view.global.WDDSocial\SiteTemplate', 
			array('title' => $page_title, 'content' => $content));
	}
	
	
	
	/**
	* get the requested article data
	*/
	
	private function getArticle($vanityURL){
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
	* Handle comment addition
	*/
	
	private function _process_form($articleID,$contentType) {
		import('wddsocial.model.WDDSocial\FormResponse');
		import('wddsocial.controller.processes.WDDSocial\CommentProcessor');
		CommentProcessor::add_comment($_POST['content'],$articleID,$contentType);
		return new FormResponse(true);
	}
}