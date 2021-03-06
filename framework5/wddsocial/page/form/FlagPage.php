<?php

namespace WDDSocial;

/*
* 
* Author: Anthony Colangelo (me@acolangelo.com)
*/

class FlagPage implements \Framework5\IExecutable {
	
	public function execute() {
		UserSession::protect();
		
		$this->db = instance(':db');
		$this->sel = instance(':sel-sql');
		$this->val = instance(':val-sql');
		$this->admin = instance(':admin-sql');
		
		$types = array('project','article','event','job','comment');
		$type = \Framework5\Request::segment(1);
		$vanityURL = \Framework5\Request::segment(2);
		if (!in_array($type, $types) or !isset($vanityURL))
			redirect('/');
		
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
			case 'comment':
				$query = $this->db->prepare($this->sel->getCommentByID);
				break;
			default:
				redirect('/');
				break;
		}
		
		if ($type != 'comment') {
			import('wddsocial.model.WDDSocial\ContentVO');
			$query->execute(array('vanityURL' => $vanityURL));
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ContentVO');
		}
		else {
			$query->execute(array('id' => $vanityURL));
			$query->setFetchMode(\PDO::FETCH_OBJ);
		}
		
		if ($query->rowCount() > 0) {
			$content = $query->fetch();
			
			if (UserValidator::is_owner($content->id,$type)) {
				redirect('/');
			}
			else {
				$data = array('id' => $content->id, 'userID' => UserSession::userid());
				
				if (UserSession::has_flagged($content->id,$type)) {
					switch ($type) {
						case 'project':
							$query = $this->db->prepare($this->admin->unflagProject);
							break;
						case 'article':
							$query = $this->db->prepare($this->admin->unflagArticle);
							break;
						case 'event':
							$query = $this->db->prepare($this->admin->unflagEvent);
							break;
						case 'job':
							$query = $this->db->prepare($this->admin->unflagJob);
							break;
						case 'comment':
							$query = $this->db->prepare($this->admin->unflagComment);
							break;
					}
					$query->execute($data);
				}
				else {
					switch ($type) {
						case 'project':
							$query = $this->db->prepare($this->admin->flagProject);
							break;
						case 'article':
							$query = $this->db->prepare($this->admin->flagArticle);
							break;
						case 'event':
							$query = $this->db->prepare($this->admin->flagEvent);
							break;
						case 'job':
							$query = $this->db->prepare($this->admin->flagJob);
							break;
						case 'comment':
							$query = $this->db->prepare($this->admin->flagComment);
							break;
					}
					$query->execute($data);
				}
				redirect("/{$_SESSION['last_request']}");
			}
		}
		else {
			redirect('/');
		}
	}
}