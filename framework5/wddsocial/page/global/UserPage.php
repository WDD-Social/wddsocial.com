<?php

namespace WDDSocial;

/*
* User Profile Page
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class UserPage implements \Framework5\IExecutable {
	
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.UserPageLang');
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
	}
	
	
	
	public function execute() {
		
		# get the request user
		$user = $this->_get_user(\Framework5\Request::segment(1));
		
		# if the user does not exist
		if ($user) {
			
			$page_title = "{$user->firstName} {$user->lastName}"; # set page title
			
			$content .= render(':section', array('section' => 'begin_content'));
			
			# display user intro
			$content .= render('wddsocial.view.profile.WDDSocial\UserIntroView', $user);
			
			# display section header
			$content .= render(':section',
				array('section' => 'begin_content_section', 'id' => 'latest',
					'classes' => array('medium', 'with-secondary', 'filterable'),
					'header' => $this->lang->text('latest'), 'extra' => 'user_latest_filters'));
			
			$paginator = new Paginator(2,20,10);
			
			# display section items
			$activity = $this->_get_user_latest($user->id, 0, $paginator->limit);
			if (count($activity) > 0) {
				foreach ($activity as $item) {
					$content .= render('wddsocial.view.content.WDDSocial\MediumDisplayView', 
						array('type' => $item->type,'content' => $item));
				}
				$next = $this->_get_user_latest($user->id, $paginator->limit, 20);
			}
			else {
				$content .= render('wddsocial.view.content.WDDSocial\NoPosts');
				$next = array();
			}
			
			
			if (count($next) > 0) {
				# display section footer
				$content .= render(':section',
					array('section' => 'end_content_section', 'id' => 'latest', 'load_more' => 'posts', 'load_more_link' => "/user/{$user->vanityURL}/{$paginator->next}"));	
			}
			
			else {
				# display section footer
				$content .= render(':section',
					array('section' => 'end_content_section', 'id' => 'latest'));	
			}
			
					
			# display users' contact info
			$content .= render('wddsocial.view.profile.WDDSocial\UserContactView', $user);
			$content .= render(':section', array('section' => 'end_content'));
		}
		
		
		# user does not exist
		else {
			redirect('/404');
		}
		
		
		# display page
		echo render(':template', 
			array('title' => $page_title, 'content' => $content));
	}
	
	
	
	/**
	* Gets the user and data
	*/
	
	private function _get_user($vanityURL){
		
		import('wddsocial.model.WDDSocial\UserVO');
		
		# query
		$data = array('vanityURL' => $vanityURL);
		$query = $this->db->prepare($this->sql->getUserByVanityURL);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
		$query->execute($data);
		return $query->fetch();
	}
	
	
	
	/**
	* Gets latest activity relating to user
	*/
	
	private function _get_user_latest($id, $start = 0, $limit = 20){
		
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		# query
		$data = array('id' => $id);
		
		if (UserSession::is_authorized()) {
			$query = $this->db->prepare($this->sql->getUserLatest . " LIMIT $start, $limit");
		}
		else {
			$query = $this->db->prepare($this->sql->getUserPublicLatest . " LIMIT $start, $limit");
		}
		
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		$query->execute($data);
		return $query->fetchAll();
	}
}