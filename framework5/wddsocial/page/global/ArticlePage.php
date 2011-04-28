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
		
		echo "<pre>";
		print_r($article);
		echo "</pre>";
			
		/*
if($user == false){
			echo render(':template', 
				array('section' => 'top', 'title' => "User Not Found"));
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
			echo "<h1>User Not Found</h1>";
			echo render('wddsocial.view.WDDSocial\SectionView',
					array('section' => 'end_content'));
		}else{
			# display site header
			echo render(':template', 
				array('section' => 'top', 'title' => "{$user->firstName} {$user->lastName}"));
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
			
			# display user intro
			echo render('wddsocial.view.WDDSocial\UserView', array('section' => 'intro', 'user' => $user));
			
			# display user's latest activity
			static::getUserLatest($user->id);
			
			# display users' contact info
			echo render('wddsocial.view.WDDSocial\UserView',array('section' => 'contact', 'user' => $user));
			
			echo render('wddsocial.view.WDDSocial\SectionView',
					array('section' => 'end_content'));
			
		}
		
		echo render(':template', 
				array('section' => 'bottom'));
*/
	}
	
	
	
	/**
	* Gets the requested article and data
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