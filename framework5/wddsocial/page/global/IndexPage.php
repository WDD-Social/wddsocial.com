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
		
		# check which home page to create, based on authorization
		if (UserSession::is_authorized()) {
			# display user dashboard page
			$content = render(':section',
				array('section' => 'begin_content', 'classes' => array('dashboard')));
			$content .= $this->_dashboard_share();
			$content .= $this->_dashboard_latest();
			$content .= $this->_public_events();
			$content .= $this->_dashboard_jobs();
			$content .= render(':section', array('section' => 'end_content'));
		}
		
		# display public home page
		else {
			$content = render(':section',
				array('section' => 'begin_content', 'classes' => array('start-page')));
			$content .= $this->_public_projects();
			$content .= $this->_public_sign_in();
			$content .= $this->_public_people();
			$content .= $this->_public_articles();
			$content .= $this->_public_events();
			$content .= render(':section', array('section' => 'end_content'));
		}
		
		echo render('wddsocial.view.global.WDDSocial\SiteTemplate', 
			array('title' => $this->lang->text('page-title'), 'content' => $content));
	}
	
	
	
	/**
	* Gets the share form
	*/
	
	private function _dashboard_share(){
				
		# display section header
		$html = render(':section', 
			array('section' => 'begin_content_section', 'id' => 'share', 'classes' => 
				array('small', 'no-margin', 'side-sticky'), 'header' => $this->lang->text('share-header')));
		
		# display form
		$html .= render('wddsocial.view.form.WDDSocial\ShareView');
		
		# display section footer
		$html .= render(':section', 
			array('section' => 'end_content_section', 'id' => 'share'));
		
		return $html;
	}
	
	
	
	/**
	* Gets and displays latest content section
	*/
	
	private function _dashboard_latest(){
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		$paginator = new Paginator(1,20,10);
		
		
		# render section header
		$html = render(':section',
			array('section' => 'begin_content_section', 'id' => 'latest',
				'classes' => array('medium', 'with-secondary', 'filterable'),
				'header' => $this->lang->text('latest-header'), 'extra' => 'latest_filters'));
		
		# render section items
		$query = $this->db->prepare($this->sql->getLatest . " LIMIT 0, {$paginator->limit}");
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		while($item = $query->fetch()){
			$html .= render('wddsocial.view.content.WDDSocial\MediumDisplayView', 
				array('type' => $item->type,'content' => $item));
		}
		
		# render section end
		$query = $this->db->prepare($this->sql->getLatest . " LIMIT {$paginator->limit}, {$paginator->per}");
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->fetch();
		
		if ($query->rowCount() > 0) {
			# display load more section footer
			$html .= render(':section',
				array('section' => 'end_content_section', 'id' => 'latest', 
					'load_more' => 'posts', 'load_more_link' => "/home/{$paginator->next}"));	
		}
		
		else {
			# display section footer
			$html .= render(':section',
				array('section' => 'end_content_section', 'id' => 'latest'));	
		}
		
		return $html;
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private function _public_sign_in(){
				
		# display section header
		$html = render(':section', 
			array('section' => 'begin_content_section', 'id' => 'sign-in', 
				'classes' => array('small', 'no-margin'), 'header' => $this->lang->text('signin-header')));
		
		# display form
		$html .= render('wddsocial.view.form.WDDSocial\SigninView');
				
		# display section footer
		$html .= render(':section', 
			array('section' => 'end_content_section', 'id' => 'sign-in'));
		
		return $html;
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private function _public_projects(){
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		# Build project array, of projects with images.
		$projects = array();
		for ($i = 0; $i < 100; $i++) {
			$j = $i + 1;
			$query = $this->db->query($this->sql->getRecentProjects . " LIMIT $i, $j");
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
			$project = $query->fetch();
			if (file_exists("images/uploads/{$project->images[0]->file}_large.jpg")) {
				array_push($projects, $project);
				if (count($projects) >= 5) {
					break;
				}
			}
			continue;
		}
		
		# query
		
		$html = render(':section', 
			array('section' => 'begin_content_section', 'id' => 'projects', 
				'classes' => array('large', 'slider'), 
				'header' => $this->lang->text('projects-header'), 'extra' => 'slider_controls'));
		
		# Display 5 projects 
		/* foreach ($projects as $project) {
			$html .= render('wddsocial.view.content.WDDSocial\LargeDisplayView', array('type' => $project->type,'content' => $project));
		} */
		
		# Display 1 project
		$html .= render('wddsocial.view.content.WDDSocial\LargeDisplayView', array('type' => $projects[0]->type,'content' => $projects[0]));
		
		# Create section footer
		$html .= render(':section', 
			array('section' => 'end_content_section', 'id' => 'projects'));
		
		return $html;
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private function _public_people(){
		import('wddsocial.model.WDDSocial\RecentPersonVO');
				
		# query
		$query = $this->db->query($this->sql->getRecentlyActivePeople);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\RecentPersonVO');
		
		$html = render(':section', 
			array('section' => 'begin_content_section', 'id' => 'people', 
				'classes' => array('small', 'image-grid'), 
				'header' => $this->lang->text('people-header')));
		
		# Create section items
		while($row = $query->fetch()){
			$html .= render('wddsocial.view.content.WDDSocial\SmallDisplayView', 
				array('type' => 'person_imagegrid','content' => $row));
		}
		
		# Create section footer
		$html .= render(':section', 
			array('section' => 'end_content_section', 'id' => 'people'));
		
		return $html;
	}
	
	
	
	/**
	* Gets and displays articles
	*/
	
	private function _public_articles(){
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		# query
		$query = $this->db->query($this->sql->getRecentArticles);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		$html = render(':section', 
			array('section' => 'begin_content_section', 'id' => 'articles', 
				'classes' => array('small', 'slider'), 'extra' => 'slider_controls', 'header' => $this->lang->text('articles-header')));
		
		# Create section items ***GETS 10 ARTICLES***
		/* while($row = $query->fetch()){
			echo render('wddsocial.view.SmallDisplayView', array('type' => $row->type,'content' => $row));
		}*/
		$row = $query->fetchAll();
		for($i = 0; $i<2; $i++){
			if(isset($row[$i])){
				$html .= render('wddsocial.view.content.WDDSocial\SmallDisplayView', 
					array('type' => $row[$i]->type,'content' => $row[$i]));
			}
		}
		
		# Create section footer
		$html .= render(':section', 
			array('section' => 'end_content_section', 'id' => 'articles'));
		
		return $html;
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
		if (UserSession::is_authorized()){
			$html = render(':section', 
				array('section' => 'begin_content_section', 'id' => 'events', 
					'classes' => array('small', 'no-margin', 'side-sticky'), 
					'header' => $this->lang->text('events-header')));
			# set limit of posts
			$limit = 3;
		}
		
		# 
		else {
			$html = render(':section', 
				array('section' => 'begin_content_section', 'id' => 'events', 
					'classes' => array('small', 'no-margin', 'slider'), 
					'header' => $this->lang->text('events-header'), 'extra' => 'slider_controls'));
			# set limit of posts
			$limit = 2;
		}		
		
		# create section items
		$row = $query->fetchAll();
		for ($i = 0; $i<$limit; $i++) {
			if (isset($row[$i])) {
				$html .= render('wddsocial.view.content.WDDSocial\SmallDisplayView', 
					array('type' => $row[$i]->type,'content' => $row[$i]));
			}
		}
		
		# create section footer
		$html .= render(':section', 
			array('section' => 'end_content_section', 'id' => 'events'));
		
		return $html;
	}
	
	
	
	/**
	* Gets and displays jobs
	*/
	
	private function _dashboard_jobs(){
		import('wddsocial.model.WDDSocial\JobVO');
		
		# query
		$query = $this->db->query($this->sql->getRecentJobs);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\JobVO');
		
		$html = render(':section', 
			array('section' => 'begin_content_section', 'id' => 'jobs', 
				'classes' => array('small', 'no-margin', 'side-sticky'), 
				'header' => $this->lang->text('jobs-header')));
		
		# Create section items
		while ($row = $query->fetch()){
			$html .= render('wddsocial.view.content.WDDSocial\SmallDisplayView', 
				array('type' => $row->type,'content' => $row));
		}
		
		# Create section footer
		$html .= render(':section', 
			array('section' => 'end_content_section', 'id' => 'jobs'));
		
		return $html;
	}
}