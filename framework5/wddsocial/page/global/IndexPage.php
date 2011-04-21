<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
* @author Anthony Colangelo (me@acolangelo.com)
*/

class IndexPage implements \Framework5\IExecutable {
	
	public static function execute() {
		import('wddsocial.sql.SelectorSQL');
		import('wddsocial.view.SectionView');
		import('wddsocial.controller.UserValidator');
		
		# DISPLAY PAGE HEADER
		echo render('wddsocial.view.TemplateView', array('section' => 'top', 'title' => 'Connecting the Full Sail University Web Community'));
	
		# CHECK WHICH HOME PAGE TO CREATE, BASED ON AUTHORIZATION
		if(\WDDSocial\UserValidator::is_authorized()){
			# CREATE USER DASHBOARD PAGE
			echo render('wddsocial.view.SectionView', array('section' => 'begin_content', 'classes' => array('dashboard')));
			echo render('wddsocial.view.FormView', array('type' => 'share'));
			static::get_latest();
			static::get_events();
			static::get_jobs();
		}else{
			# CREATE PUBLIC HOME PAGE
			echo render('wddsocial.view.SectionView', array('section' => 'begin_content', 'classes' => array('start-page')));
			static::get_projects();
			echo render('wddsocial.view.FormView', array('type' => 'signin_home'));
			static::get_people();
			static::get_articles();
			static::get_events();
		}
		
		# END CONTENT AREA
		echo render('wddsocial.view.SectionView', array('section' => 'end_content'));
		
		# CREATE FOOTER
		echo render('wddsocial.view.TemplateView', array('section' => 'bottom'));
	}
	
	
	
	/**
	* Gets and displays latest content section
	*/
	
	private static function get_latest(){
		import('wddsocial.model.DisplayVO');
		
		# GET DB INSTANCE AND QUERY
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getLatest);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');

		# CREATE SECTION HEADER
		echo render('wddsocial.view.SectionView', array('section' => 'begin_content_section', 'id' => 'latest', 'classes' => array('medium', 'with-secondary', 'filterable'), 'header' => 'Latest', 'extra' => 'latest_filters'));
		
		# CREATE SECTION ITEMS
		while($row = $query->fetch()){
			echo render('wddsocial.view.MediumDisplayView', array('type' => $row->type,'content' => $row));
		}
		
		# CREATE SECTION FOOTER
		echo render('wddsocial.view.SectionView', array('section' => 'end_content_section', 'id' => 'latest', 'load_more' => 'posts'));
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private static function get_projects(){
		import('wddsocial.model.DisplayVO');
		
		# GET DB INSTANCE AND QUERY
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getRecentProjects);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		echo render('wddsocial.view.SectionView', array('section' => 'begin_content_section', 'id' => 'projects', 'classes' => array('large', 'slider'), 'header' => 'Projects', 'extra' => 'slider_controls'));
		
		# CREATE SECTION ITEMS ***GETS 10 PROJECTS***
		/*while($row = $query->fetch()){
			echo render('wddsocial.view.LargeDisplayView', array('type' => $row->type,'content' => $row));
		}*/
		$row = $query->fetchAll();
		if(isset($row[0])){
			echo render('wddsocial.view.LargeDisplayView', array('type' => $row[0]->type,'content' => $row[0]));
		}
		
		# CREATE SECTION FOOTER
		echo render('wddsocial.view.SectionView', array('section' => 'end_content_section', 'id' => 'projects'));
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private static function get_people(){
		import('wddsocial.model.DisplayVO');
		
		# GET DB INSTANCE AND QUERY
		/*
$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getRecentArticles);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
*/
		
		echo render('wddsocial.view.SectionView', array('section' => 'begin_content_section', 'id' => 'people', 'classes' => array('small', 'image-grid'), 'header' => 'People'));
		
		# CREATE SECTION ITEMS
		/* while($row = $query->fetch()){
			echo render('wddsocial.view.SmallDisplayView', array('type' => $row->type,'content' => $row));
		}*/
		
		# CREATE SECTION FOOTER
		echo render('wddsocial.view.SectionView', array('section' => 'end_content_section', 'id' => 'people'));
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private static function get_articles(){
		import('wddsocial.model.DisplayVO');
		
		# GET DB INSTANCE AND QUERY
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getRecentArticles);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		echo render('wddsocial.view.SectionView', array('section' => 'begin_content_section', 'id' => 'articles', 'classes' => array('small', 'slider'), 'header' => 'Articles'));
		
		# CREATE SECTION ITEMS ***GETS 10 ARTICLES***
		/* while($row = $query->fetch()){
			echo render('wddsocial.view.SmallDisplayView', array('type' => $row->type,'content' => $row));
		}*/
		$row = $query->fetchAll();
		for($i = 0; $i<2; $i++){
			if(isset($row[$i])){
				echo render('wddsocial.view.SmallDisplayView', array('type' => $row[$i]->type,'content' => $row[$i]));
			}
		}
		
		# CREATE SECTION FOOTER
		echo render('wddsocial.view.SectionView', array('section' => 'end_content_section', 'id' => 'articles'));
	}
	
	
	
	/**
	* Gets and displays events
	*/
	
	private static function get_events(){
		import('wddsocial.model.EventVO');
		
		# GET DB INSTANCE AND QUERY
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = (\WDDSocial\UserValidator::is_authorized())?$db->query($sql->getUpcomingEvents):$db->query($sql->getUpcomingPublicEvents);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\EventVO');
		
		if(\WDDSocial\UserValidator::is_authorized()){
			echo render('wddsocial.view.SectionView', array('section' => 'begin_content_section', 'id' => 'events', 'classes' => array('small', 'no-margin', 'side-sticky'), 'header' => 'Events'));
			# SET LIMIT OF POSTS
			$limit = 3;
		}else{
			echo render('wddsocial.view.SectionView', array('section' => 'begin_content_section', 'id' => 'events', 'classes' => array('small', 'no-margin', 'slider'), 'header' => 'Events', 'extra' => 'slider_controls'));
			# SET LIMIT OF POSTS
			$limit = 2;
		}		
		
		# CREATE SECTION ITEMS
		$row = $query->fetchAll();
		for($i = 0; $i<$limit; $i++){
			if(isset($row[$i])){
				echo render('wddsocial.view.SmallDisplayView', array('type' => $row[$i]->type,'content' => $row[$i]));
			}
		}
		
		# CREATE SECTION FOOTER
		echo render('wddsocial.view.SectionView', array('section' => 'end_content_section', 'id' => 'events'));
	}
	
	
	
	/**
	* Gets and displays jobs
	*/
	
	private static function get_jobs(){
		import('wddsocial.model.JobVO');
		
		# GET DB INSTANCE AND QUERY
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getRecentJobs);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\JobVO');
		
		echo render('wddsocial.view.SectionView', array('section' => 'begin_content_section', 'id' => 'jobs', 'classes' => array('small', 'no-margin', 'side-sticky'), 'header' => 'Jobs'));
		
		# CREATE SECTION ITEMS
		while($row = $query->fetch()){
			echo render('wddsocial.view.SmallDisplayView', array('type' => $row->type,'content' => $row));
		}
		
		# CREATE SECTION FOOTER
		echo render('wddsocial.view.SectionView', array('section' => 'end_content_section', 'id' => 'jobs'));
	}
}