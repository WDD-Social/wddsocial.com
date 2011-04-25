<?php

namespace WDDSocial;

/*
* WDD Social site Index Page
* 
* @author tmatthews (tmatthewsdev@gmail.com)
* @author Anthony Colangelo (me@acolangelo.com)
*/

class IndexPage implements \Framework5\IExecutable {
	
	public static function execute() {

		import('wddsocial.view.WDDSocial\SectionView');
		import('wddsocial.controller.WDDSocial\UserValidator');
		
		# Display page header
		echo render(':template',
			array('section' => 'top', 'title' => 'Connecting the Full Sail University Web Community'));
		
		# Check which home page to create, based on authorization
		if (UserValidator::is_authorized()){
			# Create user dashboard page
			echo render('wddsocial.view.WDDSocial\SectionView',
				array('section' => 'begin_content', 'classes' => array('dashboard')));
			static::get_share();
			static::get_latest();
			static::get_events();
			static::get_jobs();
		}
		
		else {
			# Create public home page
			echo render('wddsocial.view.WDDSocial\SectionView',
				array('section' => 'begin_content', 'classes' => array('start-page')));
			static::get_projects();
			static::get_sign_in();
			static::get_people();
			static::get_articles();
			static::get_events();
		}
		
		# End content area
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content'));
		
		# Create footer
		echo render(':template', array('section' => 'bottom'));
	}
	
	
	
	/**
	* Gets the share form
	*/
	
	private static function get_share(){
		
		# Create section header
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'share', 'classes' => 
				array('small', 'no-margin', 'side-sticky'), 'header' => 'Share'));
		
		# Create form
		echo render('wddsocial.view.WDDSocial\FormView', array('type' => 'share'));
		
		# Create section footer
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'share'));
	}
	
	
	
	/**
	* Gets and displays latest content section
	*/
	
	private static function get_latest(){
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getLatest);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');

		# Create section header
		echo render('wddsocial.view.WDDSocial\SectionView',
			array('section' => 'begin_content_section', 'id' => 'latest',
				'classes' => array('medium', 'with-secondary', 'filterable'),
				'header' => 'Latest', 'extra' => 'latest_filters'));
		
		# Create section items
		while($row = $query->fetch()){
			echo render('wddsocial.view.WDDSocial\MediumDisplayView', 
				array('type' => $row->type,'content' => $row));
		}
		
		# Create section footer
		echo render('wddsocial.view.WDDSocial\SectionView',
			array('section' => 'end_content_section', 'id' => 'latest', 'load_more' => 'posts'));
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private static function get_sign_in(){
		
		# Create section header
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'sign-in', 
				'classes' => array('small', 'no-margin'), 'header' => 'Sign In'));
		
		# Create form
		echo render('wddsocial.view.WDDSocial\FormView', array('type' => 'sign_in'));
				
		# Create section footer
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'sign-in'));
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private static function get_projects(){
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getRecentProjects);
		$query->setFetchMode(\PDO::FETCH_CLASS,'DisplayVO');
		
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'projects', 
				'classes' => array('large', 'slider'), 
				'header' => 'Projects', 'extra' => 'slider_controls'));
		
		# Create section items ***GETS 10 PROJECTS***
		/*while($row = $query->fetch()){
			echo render('wddsocial.view.LargeDisplayView', array('type' => $row->type,'content' => $row));
		}*/
		$row = $query->fetchAll();
		if(isset($row[0])){
			echo render('wddsocial.view.WDDSocial\LargeDisplayView', 
				array('type' => $row[0]->type,'content' => $row[0]));
		}
		
		# Create section footer
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'projects'));
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private static function get_people(){
		import('wddsocial.model.WDDSocial\RecentPersonVO');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getRecentlyActivePeople);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\RecentPersonVO');
		
		echo render('wddsocial.view.SectionView', 
			array('section' => 'begin_content_section', 'id' => 'people', 
				'classes' => array('small', 'image-grid'), 
				'header' => 'People'));
		
		# Create section items
		while($row = $query->fetch()){
			echo render('wddsocial.view.WDDSocial\SmallDisplayView', 
				array('type' => 'person_imagegrid','content' => $row));
		}
		
		# Create section footer
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'people'));
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private static function get_articles(){
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getRecentArticles);
		$query->setFetchMode(\PDO::FETCH_CLASS,'DisplayVO');
		
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'articles', 
				'classes' => array('small', 'slider'), 'header' => 'Articles'));
		
		# Create section items ***GETS 10 ARTICLES***
		/* while($row = $query->fetch()){
			echo render('wddsocial.view.SmallDisplayView', array('type' => $row->type,'content' => $row));
		}*/
		$row = $query->fetchAll();
		for($i = 0; $i<2; $i++){
			if(isset($row[$i])){
				echo render('wddsocial.view.WDDSocial\SmallDisplayView', 
					array('type' => $row[$i]->type,'content' => $row[$i]));
			}
		}
		
		# Create section footer
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'articles'));
	}
	
	
	
	/**
	* Gets and displays events
	*/
	
	private static function get_events(){
		import('wddsocial.model.WDDSocial\EventVO');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = (UserValidator::is_authorized())?$db->query($sql->getUpcomingEvents):$db->query($sql->getUpcomingPublicEvents);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\EventVO');
		
		if(UserValidator::is_authorized()){
			echo render('wddsocial.view.WDDSocial\SectionView', 
				array('section' => 'begin_content_section', 'id' => 'events', 
					'classes' => array('small', 'no-margin', 'side-sticky'), 
					'header' => 'Events'));
			# Set limit of posts
			$limit = 3;
		}else{
			echo render('wddsocial.view.WDDSocial\SectionView', 
				array('section' => 'begin_content_section', 'id' => 'events', 
					'classes' => array('small', 'no-margin', 'slider'), 
					'header' => 'Events', 'extra' => 'slider_controls'));
			# Set limit of posts
			$limit = 2;
		}		
		
		# CREATE SECTION ITEMS
		$row = $query->fetchAll();
		for($i = 0; $i<$limit; $i++){
			if(isset($row[$i])){
				echo render('wddsocial.view.WDDSocial\SmallDisplayView', 
					array('type' => $row[$i]->type,'content' => $row[$i]));
			}
		}
		
		# Create section footer
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'events'));
	}
	
	
	
	/**
	* Gets and displays jobs
	*/
	
	private static function get_jobs(){
		import('wddsocial.model.WDDSocial\JobVO');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getRecentJobs);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\JobVO');
		
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'jobs', 
				'classes' => array('small', 'no-margin', 'side-sticky'), 
				'header' => 'Jobs'));
		
		# Create section items
		while($row = $query->fetch()){
			echo render('wddsocial.view.WDDSocial\SmallDisplayView', 
				array('type' => $row->type,'content' => $row));
		}
		
		# Create section footer
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'jobs'));
	}
}