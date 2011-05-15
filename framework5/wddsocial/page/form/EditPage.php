<?php

namespace WDDSocial;

/*
* 
* Author: Anthony Colangelo (me@acolangelo.com)
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class EditPage implements \Framework5\IExecutable {
	
	public function execute() {
		UserSession::protect();
		
		$types = array('project','article','event','job');
		$type = \Framework5\Request::segment(1);
		$vanityURL = \Framework5\Request::segment(2);
		if (!in_array($type, $types) or !isset($vanityURL))
			redirect('/');
		
		import('wddsocial.model.WDDSocial\ContentVO');
		
		$this->db = instance(':db');
		$this->sel = instance(':sel-sql');
		$this->val = instance(':val-sql');
		$this->admin = instance(':admin-sql');
		
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
			default:
				redirect('/');
				break;
		}
		$query->execute(array('vanityURL' => $vanityURL));
		
		if ($query->rowCount() > 0) {
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ContentVO');
			$content = $query->fetch();
			switch ($type) {
				case 'project':
					$query = $this->db->prepare($this->val->isUserProjectOwner);
					$query->execute(array('userID' => UserSession::userid(), 'projectID' => $content->id));
					break;
				case 'article':
					$query = $this->db->prepare($this->val->isUserArticleOwner);
					$query->execute(array('userID' => UserSession::userid(), 'articleID' => $content->id));
					break;
				case 'event':
					$query = $this->db->prepare($this->val->isUserEventOwner);
					$query->execute(array('userID' => UserSession::userid(), 'eventID' => $content->id));
					break;
				case 'job':
					$query = $this->db->prepare($this->val->isUserJobOwner);
					$query->execute(array('userID' => UserSession::userid(), 'jobID' => $content->id));
					break;
			}
			if ($query->rowCount() == 0) {
				redirect('/');
			}
		}
		else {
			redirect('/');
		}
		
		echo "<pre>";
		print_r($content);
		echo "</pre>";
		
		# handle form submission
		if (isset($_POST['submit'])){
			$response = $this->_process_form();
			
			# redirect user on success
			if ($response->status) {
				# redirect user to new content page
				redirect("{$response->message}");
			}
		}
	}
	
	
	
	/**
	* Handle content editing
	*/
	
	private function _process_form() {
	}
}