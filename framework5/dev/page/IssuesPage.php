<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class IssuesPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->db = instance('core.controller.Framework5\Database');
	}
	
	
	public function execute() {
		
		$issue_id = \Framework5\Request::segment(2);
		
		if ($issue_id) {
			$content = render('dev.view.issue.Framework5\Dev\IssueView', $this->issue_info($issue_id));
		}
		
		else {
			$content = render('dev.view.issue.Framework5\Dev\IssuesView', $this->get_issues());
		}
		
		# display output
		echo render(':template', array('title' => 'Bug Tracker', 'content' => $content));
	}
	
	
	
	public function get_issues($limit = 0, $page = 0) {
		
		# get the db object
		$sql = "
			SELECT id, request_id, user_id, timestamp, message 
			FROM fw5_issues 
			ORDER BY id DESC";
		
		$query = $this->db->query($sql);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		return $query->fetchAll();
	}
	
	
	
	public function issue_info($id) {
		
		# get the db object
		$sql = "
			SELECT id, request_id, user_id, timestamp, message 
			FROM fw5_issues
			WHERE id = :id";
		
		$query = $this->db->prepare($sql);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute(array('id' => $id));
		return $query->fetch();
	}
}