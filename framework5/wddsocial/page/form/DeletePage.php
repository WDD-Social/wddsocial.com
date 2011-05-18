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
		
		if ($type = 'user')
			import('wddsocial.model.WDDSocial\ContentVO');
		else
			import('wddsocial.model.WDDSocial\UserVO');
		
		switch ($type) {
			case 'project':
				$query = $this->db->prepare($this->sel->getProjectByVanityURL);
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ContentVO');
				break;
			case 'article':
				$query = $this->db->prepare($this->sel->getArticleByVanityURL);
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ContentVO');
				break;
			case 'event':
				$query = $this->db->prepare($this->sel->getEventByVanityURL);
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ContentVO');
				break;
			case 'job':
				$query = $this->db->prepare($this->sel->getJobByVanityURL);
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ContentVO');
				break;
			case 'user':
				$query = $this->db->prepare($this->sel->getUserByVanityURL);
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
				break;
			default:
				redirect('/');
				break;
		}
		
		$typeTitle = ucfirst($content->type);
		
		# display site header
		echo render(':template', array('section' => 'top', 'title' => "Delete {$typeTitle} | {$content->title}"));
		
		# open content section
		echo render(':section', array('section' => 'begin_content'));
		
		# end content section
		echo render(':section', array('section' => 'end_content'));
		
		# display site footer
		echo render(':template', array('section' => 'bottom'));
	}
}