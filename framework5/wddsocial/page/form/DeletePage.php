<?php

namespace WDDSocial;

/*
* 
* Author: Anthony Colangelo (me@acolangelo.com)
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class DeletePage implements \Framework5\IExecutable {
	
	public function execute() {
		UserSession::protect();
		
		$this->db = instance(':db');
		$this->sel = instance(':sel-sql');
		$this->val = instance(':val-sql');
		$this->admin = instance(':admin-sql');
		
		$types = array('project','article','event','job','user');
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
		else {
			import('wddsocial.model.WDDSocial\UserVO');
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
		}
			
		$query->execute(array('vanityURL' => $vanityURL));
		$content = $query->fetch();
		if ($type == 'user') $content->title = $content->firstName . ' ' . $content->lastName;
		$typeTitle = ucfirst($type);
		
		# display site header
		echo render(':template', array('section' => 'top', 'title' => "Delete {$typeTitle} | {$content->title}"));
		
		# open content section
		echo render(':section', array('section' => 'begin_content'));
		
		/*
echo "<pre>";
		print_r($content);
		echo "</pre>";
*/
		
		# end content section
		echo render(':section', array('section' => 'end_content'));
		
		# display site footer
		echo render(':template', array('section' => 'bottom'));
	}
}