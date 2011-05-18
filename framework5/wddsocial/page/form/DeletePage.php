<?php

namespace WDDSocial;

/*
* 
* Author: Anthony Colangelo (me@acolangelo.com)
*/

class DeletePage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->db = instance(':db');
		$this->sel = instance(':sel-sql');
		$this->val = instance(':val-sql');
		$this->admin = instance(':admin-sql');
	}
	
	
	public function execute() {
		
		# require user auth
		UserSession::protect();
		
		# uri vars
		$type = \Framework5\Request::segment(1);
		$vanityURL = \Framework5\Request::segment(2);
		
		# handle form submission
		if (isset($_POST['submit']) and $_POST['submit'] == 'Delete'){
			$response = $this->_process_form();
			
			# redirect user on success
			if ($response->status) {
				# redirect user to new content page
				redirect("{$response->message}");
			}
		}
		
		# set valid types
		$types = array('project', 'article', 'event', 'job', 'user');
		
		# redirect invalid types
		if (!in_array($type, $types) or !isset($vanityURL)) redirect('/');
		
		# set query based on type
		switch ($type) {
			case 'project':
				$query = $this->db->prepare($this->sel->getProjectByVanityURL);
				break;
			case 'article':
				$query = $this->db->prepare($this->sel->getArticleByVanityURL);
				break;
			case 'event':
				$query = $this->db->prepare($this->sel->getEventByVanityURL);
				break;
			case 'job':
				$query = $this->db->prepare($this->sel->getJobByVanityURL);
				break;
			case 'user':
				$query = $this->db->prepare($this->sel->getUserByVanityURL);
				break;
			default:
				redirect('/');
				break;
		}
		
		
		if ($type != 'user') {
			import('wddsocial.model.WDDSocial\ContentVO');
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ContentVO');
		}
		else if ($type == 'job') {
			import('wddsocial.model.WDDSocial\JobVO');
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\JobVO');
		}
		else {
			import('wddsocial.model.WDDSocial\UserVO');
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
		}
		
		# get the content
		$query->execute(array('vanityURL' => $vanityURL));
		
		# check if user is content owner
		if ($query->rowCount() > 0) {
			$content = $query->fetch();
			switch ($type) {
				case 'project':
					if (!UserValidator::is_project_owner($content->id)) {
						redirect('/');
					}
					break;
				case 'article':
					if (!UserValidator::is_article_owner($content->id)) {
						redirect('/');
					}
					break;
				case 'event':
					if (!UserValidator::is_event_owner($content->id)) {
						redirect('/');
					}
					break;
				case 'job':
					if (!UserValidator::is_job_owner($content->id)) {
						redirect('/');
					}
				case 'user':
					if (UserSession::userid() != ($content->id)) {
						redirect('/');
					}
					break;
			}
		}
		
		# user not content owner
		else {
			redirect('/');
		}
		
		if ($type == 'user') $content->title = "{$content->firstName} {$content->lastName}";
		$typeTitle = ucfirst($type);
		
		
		# page title
		$page_title = "Delete {$typeTitle} | {$content->title}";
		
		# open content section
		$html.= render(':section', array('section' => 'begin_content'));
		
		# display delete form
		$html.= render('wddsocial.view.form.WDDSocial\DeleteView', 
			array('content' => $content, 'type' => $type, 'error' => $response->message));
		
		# end content section
		$html.= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', 
			array('title' => $page_title, 'content' => $html));
	}
	
	
	
	/**
	* Handle content deleting
	*/
	
	private function _process_form() {
		
		import('wddsocial.model.WDDSocial\FormResponse');
		import('wddsocial.controller.processes.WDDSocial\Deleter');
		
		switch ($_POST['type']) {
			case 'project':
				$query = $this->db->prepare($this->sel->getProjectByID);
				break;
			
			case 'article':
				$query = $this->db->prepare($this->sel->getArticleByID);
				break;
			
			case 'event':
				$query = $this->db->prepare($this->sel->getEventByID);
				break;
			
			case 'job':
				$query = $this->db->prepare($this->sel->getJobByID);
				break;
			
			case 'user':
				$query = $this->db->prepare($this->sel->getUserByID);
				break;
			
			default:
				redirect('/');
				break;
		}
		
		
		if ($_POST['type'] != 'user') {
			import('wddsocial.model.WDDSocial\ContentVO');
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ContentVO');
		}
		else if ($_POST['type'] == 'job') {
			import('wddsocial.model.WDDSocial\JobVO');
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\JobVO');
		}
		else {
			import('wddsocial.model.WDDSocial\UserVO');
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
		}
		
		
		$query->execute(array('id' => $_POST['id']));
		
		if ($query->rowCount() > 0) {
			$content = $query->fetch();
			
			if ($_POST['type'] == 'user') {
				# delete user from teams
				$query = $this->db->prepare($this->admin->deleteUserFromProjectTeams);
				$query->execute(array('userID' => $content->id));
				
				# get projects owned by user
				$query = $this->db->prepare($this->sel->getProjectsOwnedByUser);
				$query->execute(array('userID' => $content->id));
				
				# if projects exist, change their owner
				if ($query->rowCount() > 0) {
					$query->setFetchMode(\PDO::FETCH_OBJ);
					while ($project = $query->fetch()) {
						if ($project->memberCount > 0) {
							$newquery = $this->db->prepare($this->admin->changeProjectOwner);
							$newquery->execute(array('projectID' => $project->id));
						}
					}
				}
				
				
				
				# delete user from article authors
				$query = $this->db->prepare($this->admin->deleteUserFromArticleAuthors);
				$query->execute(array('userID' => $content->id));
				
				# get articles owned by user
				$query = $this->db->prepare($this->sel->getArticlesOwnedByUser);
				$query->execute(array('userID' => $content->id));
				
				# if articles exist, change their owner
				if ($query->rowCount() > 0) {
					$query->setFetchMode(\PDO::FETCH_OBJ);
					while ($article = $query->fetch()) {
						if ($article->authorCount > 0) {
							$newquery = $this->db->prepare($this->admin->changeArticleOwner);
							$newquery->execute(array('articleID' => $article->id));
						}
					}
				}
				
				# delete user avatar
				Deleter::delete_user_avatar($content->avatar);
				
				# delete user
				$query = $this->db->prepare($this->admin->deleteUser);
				$query->execute(array('id' => $content->id));
				
				# sign user out
				UserSession::signout();
			}
			
			else {
				foreach ($content->images as $image) {
					Deleter::delete_content_image($image->file);
				}
				
				$data = array('id' => $content->id);
				switch ($_POST['type']) {
					case 'project':
						$query = $this->db->prepare($this->admin->deleteProject);
						$query->execute($data);
						break;
					
					case 'article':
						$query = $this->db->prepare($this->admin->deleteArticle);
						$query->execute($data);
						break;
					
					case 'event':
						$query = $this->db->prepare($this->admin->deleteEvent);
						$query->execute($data);
						break;
					
					case 'job':
						Deleter::delete_job_avatar($content->avatar);
						$query = $this->db->prepare($this->admin->deleteJob);
						$query->execute($data);
						break;
				}
			}	
		}
		
		else {
			return new FormResponse(false,'Uh oh, looks like there was an error. Please try again.');
		}
		
		return new FormResponse(true,'/');
	}
}