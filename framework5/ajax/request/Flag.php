<?php

namespace Ajax;

/**
* Flags content
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class Flag implements \Framework5\IExecutable {
	
	public function execute() {
		
		# check user auth
		if (!\WDDSocial\UserSession::is_authorized()) {
			$response->status = false;
			echo json_encode($response);
		}
		
		$types = array('project','article','event','job','comment');
		$type = $_POST['type'];
		$vanityURL = $_POST['vanityURL'];
		
		if (!isset($type) or $type == '' or !in_array($type, $types) or !isset($vanityURL) or $vanityURL == '') {
			$response->status = false;
			echo json_encode($response);
		}
		
		$this->db = instance(':db');
		$this->sel = instance(':sel-sql');
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
			case 'comment':
				$query = $this->db->prepare($this->sel->getCommentByID);
				break;
			default:
				$response->status = false;
				echo json_encode($response);
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
			
			if (\WDDSocial\UserValidator::is_owner($content->id,$type)) {
				$response->status = false;
				echo json_encode($response);
			}
			else {
				$data = array('id' => $content->id, 'userID' => \WDDSocial\UserSession::userid());
				
				if (\WDDSocial\UserSession::has_flagged($content->id,$type)) {
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
				
				// success
				$response->status = true;
				echo json_encode($response);
			}
		}
		else {
			$response->status = false;
			echo json_encode($response);
		}
	}
}