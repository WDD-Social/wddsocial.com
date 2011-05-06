<?php

namespace WDDSocial;

/*
* WDD Social site Index Page
* 
* @author tmatthews (tmatthewsdev@gmail.com)
* @author Anthony Colangelo (me@acolangelo.com)
*/

class IndexPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# 
		$lang = new \Framework5\Lang('wddsocial.lang.page.global.IndexPageLang');
		
		# site header
		echo render(':template',
			array('section' => 'top', 'title' => $lang->text('page_title')));
		
		# Check which home page to create, based on authorization
		if (UserSession::is_authorized()) {
			# Create user dashboard page
			echo render(':section',
				array('section' => 'begin_content', 'classes' => array('dashboard')));
			$this->dashboard_share();
			$this->dashboard_latest();
			$this->public_events();
			$this->dashboard_jobs();
		}
		
		# display public home page
		else {
			echo render(':section',
				array('section' => 'begin_content', 'classes' => array('start-page')));
			static::public_projects();
			static::public_sign_in();
			static::public_people();
			static::public_articles();
			static::public_events();
		}
		
		# end content area
		echo render(':section', 
			array('section' => 'end_content'));
		
		# site footer
		echo render(':template', array('section' => 'bottom'));
	}
	
	
	
	/**
	* Gets the share form
	*/
	
	private function dashboard_share(){
		
		$lang = new \Framework5\Lang('wddsocial.lang.page.global.IndexPageLang');
		
		# Create section header
		echo render(':section', 
			array('section' => 'begin_content_section', 'id' => 'share', 'classes' => 
				array('small', 'no-margin', 'side-sticky'), 'header' => $lang->text('share_header')));
		
		# Create form
		echo render('wddsocial.view.form.WDDSocial\ShareView');
		
		# Create section footer
		echo render(':section', 
			array('section' => 'end_content_section', 'id' => 'share'));
	}
	
	
	
	/**
	* Gets and displays latest content section
	*/
	
	private function dashboard_latest(){
		import('wddsocial.model.WDDSocial\DisplayVO');
		$lang = new \Framework5\Lang('wddsocial.lang.page.global.IndexPageLang');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getLatest);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		# Create section header
		echo render(':section',
			array('section' => 'begin_content_section', 'id' => 'latest',
				'classes' => array('medium', 'with-secondary', 'filterable'),
				'header' => $lang->text('latest_header'), 'extra' => 'latest_filters'));
		
		# Create section items
		while($row = $query->fetch()){
			echo render('wddsocial.view.WDDSocial\MediumDisplayView', 
				array('type' => $row->type,'content' => $row));
		}
		
		# Create section footer
		echo render(':section',
			array('section' => 'end_content_section', 'id' => 'latest', 'load_more' => 'posts'));
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private function public_sign_in(){
		
		$lang = new \Framework5\Lang('wddsocial.lang.page.global.IndexPageLang');
		
		# Create section header
		echo render(':section', 
			array('section' => 'begin_content_section', 'id' => 'sign-in', 
				'classes' => array('small', 'no-margin'), 'header' => $lang->text('signin_header')));
		
		# Create form
		echo render('wddsocial.view.form.WDDSocial\SigninView');
				
		# Create section footer
		echo render(':section', 
			array('section' => 'end_content_section', 'id' => 'sign-in'));
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private function public_projects(){
		import('wddsocial.model.WDDSocial\DisplayVO');
		$lang = new \Framework5\Lang('wddsocial.lang.page.global.IndexPageLang');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getRecentProjects);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		echo render(':section', 
			array('section' => 'begin_content_section', 'id' => 'projects', 
				'classes' => array('large', 'slider'), 
				'header' => $lang->text('projects_header'), 'extra' => 'slider_controls'));
		
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
		echo render(':section', 
			array('section' => 'end_content_section', 'id' => 'projects'));
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private function public_people(){
		import('wddsocial.model.WDDSocial\RecentPersonVO');
		
		$lang = new \Framework5\Lang('wddsocial.lang.page.global.IndexPageLang');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getRecentlyActivePeople);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\RecentPersonVO');
		
		echo render(':section', 
			array('section' => 'begin_content_section', 'id' => 'people', 
				'classes' => array('small', 'image-grid'), 
				'header' => $lang->text('people_header')));
		
		# Create section items
		while($row = $query->fetch()){
			echo render('wddsocial.view.WDDSocial\SmallDisplayView', 
				array('type' => 'person_imagegrid','content' => $row));
		}
		
		# Create section footer
		echo render(':section', 
			array('section' => 'end_content_section', 'id' => 'people'));
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private function public_articles(){
		import('wddsocial.model.WDDSocial\DisplayVO');
		$lang = new \Framework5\Lang('wddsocial.lang.page.global.IndexPageLang');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getRecentArticles);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		echo render(':section', 
			array('section' => 'begin_content_section', 'id' => 'articles', 
				'classes' => array('small', 'slider'), 'header' => $lang->text('articles_header')));
		
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
		echo render(':section', 
			array('section' => 'end_content_section', 'id' => 'articles'));
	}
	
	
	
	/**
	* Gets and displays events
	*/
	
	private function public_events(){
		import('wddsocial.model.WDDSocial\EventVO');
		$lang = new \Framework5\Lang('wddsocial.lang.page.global.IndexPageLang');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = (UserSession::is_authorized())?$db->query($sql->getUpcomingEvents):$db->query($sql->getUpcomingPublicEvents);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\EventVO');
		
		if(UserSession::is_authorized()){
			echo render(':section', 
				array('section' => 'begin_content_section', 'id' => 'events', 
					'classes' => array('small', 'no-margin', 'side-sticky'), 
					'header' => $lang->text('events_header')));
			# Set limit of posts
			$limit = 3;
		}else{
			echo render(':section', 
				array('section' => 'begin_content_section', 'id' => 'events', 
					'classes' => array('small', 'no-margin', 'slider'), 
					'header' => $lang->text('events_header'), 'extra' => 'slider_controls'));
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
		echo render(':section', 
			array('section' => 'end_content_section', 'id' => 'events'));
	}
	
	
	
	/**
	* Gets and displays jobs
	*/
	
	private function dashboard_jobs(){
		import('wddsocial.model.WDDSocial\JobVO');
		$lang = new \Framework5\Lang('wddsocial.lang.page.global.IndexPageLang');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getRecentJobs);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\JobVO');
		
		echo render(':section', 
			array('section' => 'begin_content_section', 'id' => 'jobs', 
				'classes' => array('small', 'no-margin', 'side-sticky'), 
				'header' => $lang->text('jobs_header')));
		
		# Create section items
		while($row = $query->fetch()){
			echo render('wddsocial.view.WDDSocial\SmallDisplayView', 
				array('type' => $row->type,'content' => $row));
		}
		
		# Create section footer
		echo render(':section', 
			array('section' => 'end_content_section', 'id' => 'jobs'));
	}
}