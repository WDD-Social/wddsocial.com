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
		$user = $this->getUser(\Framework5\Request::segment(1));
		
		# if the user does not exist
		if ($user) {
			
			# display site header
			echo render(':template', 
				array('section' => 'top', 'title' => "{$user->firstName} {$user->lastName}"));
			echo render(':section', array('section' => 'begin_content'));
			
			# display user intro
			echo render('wddsocial.view.profile.WDDSocial\UserIntroView', $user);
			
			# display section header
			echo render(':section',
				array('section' => 'begin_content_section', 'id' => 'latest',
					'classes' => array('medium', 'with-secondary', 'filterable'),
					'header' => $this->lang->text('latest'), 'extra' => 'user_latest_filters'));
			
			$paginator = new Paginator(2,20,10);
			
			# display section items
			$activity = $this->getUserLatest($user->id, 0, $paginator->limit);
			foreach ($activity as $item) {
				echo render('wddsocial.view.content.WDDSocial\MediumDisplayView', 
					array('type' => $item->type,'content' => $item));
			}
			
			$next = $this->getUserLatest($user->id, $paginator->limit, 20);
			
			if (count($next) > 0) {
				# display section footer
				echo render(':section',
					array('section' => 'end_content_section', 'id' => 'latest', 'load_more' => 'posts', 'load_more_link' => "/user/{$user->vanityURL}/{$paginator->next}"));	
			}		
			else {
				# display section footer
				echo render(':section',
					array('section' => 'end_content_section', 'id' => 'latest'));	
			}
					
			# display users' contact info
			echo render('wddsocial.view.profile.WDDSocial\UserContactView', $user);
		}
		
		
		# user does not exist
		else {
			
			# display site header
			echo render(':template', array('section' => 'top', 'title' => "User Not Found"));
			echo render(':section', array('section' => 'begin_content'));
			
			# display user not found view
			echo render('wddsocial.view.profile.WDDSocial\NotFoundView');
		}
		
		
		# display site footer
		echo render(':section', array('section' => 'end_content'));
		echo render(':template', array('section' => 'bottom'));
	}
	
	
	
	/**
	* Gets the user and data
	*/
	
	private function getUser($vanityURL){
		
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
	
	private function getUserLatest($id, $start = 0, $limit = 20){
		
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		# query
		$data = array('id' => $id);
		$query = $this->db->prepare($this->sql->getUserLatest . " LIMIT $start, $limit");
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		$query->execute($data);
		return $query->fetchAll();
	}
}