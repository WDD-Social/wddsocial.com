<?php

namespace WDDSocial;

/*
* WDD Social site Index Page
* 
* @author tmatthews (tmatthewsdev@gmail.com)
* @author Anthony Colangelo (me@acolangelo.com)
*/

class IndexPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.IndexPageLang');
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
	}
	
	
	
	public function execute() {
		
		# site header
		echo render(':template',
			array('section' => 'top', 'title' => $this->lang->text('page_title')));
		
		# check which home page to create, based on authorization
		if (UserSession::is_authorized()) {
			# display user dashboard page
			echo render(':section',
				array('section' => 'begin_content', 'classes' => array('dashboard')));
			$this->_dashboard_share();
			$this->_dashboard_latest();
			$this->_public_events();
			$this->_dashboard_jobs();
		}
		
		# display public home page
		else {
			echo render(':section',
				array('section' => 'begin_content', 'classes' => array('start-page')));
			$this->_public_projects();
			$this->_public_sign_in();
			$this->_public_people();
			$this->_public_articles();
			$this->_public_events();
		}
		
		# display site footer
		echo render(':section', array('section' => 'end_content'));
		echo render(':template', array('section' => 'bottom'));
	}
	
	
	
	/**
	* Gets the share form
	*/
	
	private function _dashboard_share(){
				
		# display section header
		echo render(':section', 
			array('section' => 'begin_content_section', 'id' => 'share', 'classes' => 
				array('small', 'no-margin', 'side-sticky'), 'header' => $this->lang->text('share_header')));
		
		# display form
		echo render('wddsocial.view.form.WDDSocial\ShareView');
		
		# display section footer
		echo render(':section', 
			array('section' => 'end_content_section', 'id' => 'share'));
	}
	
	
	
	/**
	* Gets and displays latest content section
	*/
	
	private function _dashboard_latest(){
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		# query
		$query = $this->db->query($this->sql->getLatest);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		# display section header
		echo render(':section',
			array('section' => 'begin_content_section', 'id' => 'latest',
				'classes' => array('medium', 'with-secondary', 'filterable'),
				'header' => $this->lang->text('latest_header'), 'extra' => 'latest_filters'));
		
		# display section items
		while($row = $query->fetch()){
			echo render('wddsocial.view.content.WDDSocial\MediumDisplayView', 
				array('type' => $row->type,'content' => $row));
		}
		
		# display section footer
		echo render(':section',
			array('section' => 'end_content_section', 'id' => 'latest', 'load_more' => 'posts'));
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private function _public_sign_in(){
				
		# display section header
		echo render(':section', 
			array('section' => 'begin_content_section', 'id' => 'sign-in', 
				'classes' => array('small', 'no-margin'), 'header' => $this->lang->text('signin_header')));
		
		# display form
		echo render('wddsocial.view.form.WDDSocial\SigninView');
				
		# display section footer
		echo render(':section', 
			array('section' => 'end_content_section', 'id' => 'sign-in'));
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private function _public_projects(){
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		# query
		$query = $this->db->query($this->sql->getRecentProjects);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		echo render(':section', 
			array('section' => 'begin_content_section', 'id' => 'projects', 
				'classes' => array('large', 'slider'), 
				'header' => $this->lang->text('projects_header'), 'extra' => 'slider_controls'));
		
		# Create section items ***GETS 10 PROJECTS***
		/*while($row = $query->fetch()){
			echo render('wddsocial.view.LargeDisplayView', array('type' => $row->type,'content' => $row));
		}*/
		$row = $query->fetchAll();
		if(isset($row[0])){
			echo render('wddsocial.view.content.WDDSocial\LargeDisplayView', 
				array('type' => $row[0]->type,'content' => $row[0]));
		}
		
		# Create section footer
		echo render(':section', 
			array('section' => 'end_content_section', 'id' => 'projects'));
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private function _public_people(){
		import('wddsocial.model.WDDSocial\RecentPersonVO');
				
		# query
		$query = $this->db->query($this->sql->getRecentlyActivePeople);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\RecentPersonVO');
		
		echo render(':section', 
			array('section' => 'begin_content_section', 'id' => 'people', 
				'classes' => array('small', 'image-grid'), 
				'header' => $this->lang->text('people_header')));
		
		# Create section items
		while($row = $query->fetch()){
			echo render('wddsocial.view.content.WDDSocial\SmallDisplayView', 
				array('type' => 'person_imagegrid','content' => $row));
		}
		
		# Create section footer
		echo render(':section', 
			array('section' => 'end_content_section', 'id' => 'people'));
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private function _public_articles(){
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		# query
		$query = $this->db->query($this->sql->getRecentArticles);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		echo render(':section', 
			array('section' => 'begin_content_section', 'id' => 'articles', 
				'classes' => array('small', 'slider'), 'extra' => 'slider_controls', 'header' => $this->lang->text('articles_header')));
		
		# Create section items ***GETS 10 ARTICLES***
		/* while($row = $query->fetch()){
			echo render('wddsocial.view.SmallDisplayView', array('type' => $row->type,'content' => $row));
		}*/
		$row = $query->fetchAll();
		for($i = 0; $i<2; $i++){
			if(isset($row[$i])){
				echo render('wddsocial.view.content.WDDSocial\SmallDisplayView', 
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
	
	private function _public_events(){
		import('wddsocial.model.WDDSocial\EventVO');
		
		# query
		if (UserSession::is_authorized()) $query = $this->db->query($this->sql->getUpcomingEvents);
		else $query = $this->db->query($this->sql->getUpcomingPublicEvents);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\EventVO');
		
		# 
		if(UserSession::is_authorized()){
			echo render(':section', 
				array('section' => 'begin_content_section', 'id' => 'events', 
					'classes' => array('small', 'no-margin', 'side-sticky'), 
					'header' => $this->lang->text('events_header')));
			# set limit of posts
			$limit = 3;
		}
		
		# 
		else{
			echo render(':section', 
				array('section' => 'begin_content_section', 'id' => 'events', 
					'classes' => array('small', 'no-margin', 'slider'), 
					'header' => $this->lang->text('events_header'), 'extra' => 'slider_controls'));
			# set limit of posts
			$limit = 2;
		}		
		
		# create section items
		$row = $query->fetchAll();
		for($i = 0; $i<$limit; $i++){
			if(isset($row[$i])){
				echo render('wddsocial.view.content.WDDSocial\SmallDisplayView', 
					array('type' => $row[$i]->type,'content' => $row[$i]));
			}
		}
		
		# create section footer
		echo render(':section', 
			array('section' => 'end_content_section', 'id' => 'events'));
	}
	
	
	
	/**
	* Gets and displays jobs
	*/
	
	private function _dashboard_jobs(){
		import('wddsocial.model.WDDSocial\JobVO');
		
		# query
		$query = $this->db->query($this->sql->getRecentJobs);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\JobVO');
		
		echo render(':section', 
			array('section' => 'begin_content_section', 'id' => 'jobs', 
				'classes' => array('small', 'no-margin', 'side-sticky'), 
				'header' => $this->lang->text('jobs_header')));
		
		# Create section items
		while($row = $query->fetch()){
			echo render('wddsocial.view.content.WDDSocial\SmallDisplayView', 
				array('type' => $row->type,'content' => $row));
		}
		
		# Create section footer
		echo render(':section', 
			array('section' => 'end_content_section', 'id' => 'jobs'));
	}
}