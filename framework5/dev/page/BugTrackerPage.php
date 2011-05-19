<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class BugTrackerPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->db = instance('core.controller.Framework5\Database');
	}
	
	
	public function execute() {
		
		$bug_id = \Framework5\Request::segment(2);
		
		if ($bug_id) {
			$content = render('dev.view.Framework5\Dev\BugInfoView', $this->bug_info($bug_id));
		}
		
		else {
			$content = render('dev.view.Framework5\Dev\BugListView', $this->get_bugs());
		}
		
		echo render('dev.view.Framework5\Dev\TemplateView',
			array('title' => 'Bug Tracker', 'content' => $content));
	}
	
	
	
	public function get_bugs($limit = 0, $page = 0) {
		
		# get the db object
		$sql = "
			SELECT id, request_id, user_id, message 
			FROM fw5_bugs 
			ORDER BY id DESC";
		
		$query = $this->db->query($sql);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		return $query->fetchAll();
	}
	
	
	
	public function bug_info($id) {
		
		# get the db object
		$sql = "
			SELECT id, request_id, user_id, message 
			FROM fw5_bugs
			WHERE id = :id";
		
		$query = $this->db->prepare($sql);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute(array('id' => $id));
		return $query->fetch();
	}
}