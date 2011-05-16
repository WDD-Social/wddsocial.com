<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class BugTrackerPage implements \Framework5\IExecutable {
	
	public function execute() {
		echo render('dev.view.Framework5\Dev\TemplateView');
		echo render('dev.view.Framework5\Dev\PageHeader');
		echo render('dev.view.Framework5\Dev\BugListDisplayView', $this->get_bugs());
	}
	
	
	public function get_bugs($limit = 0, $page = 0) {
		
		# get the db object
		$sql = "
			SELECT id, request_id, user_id, message 
			FROM fw5_bugs 
			ORDER BY id DESC";
		
		$db = instance('core.controller.Framework5\Database');
		$query = $db->query($sql);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		return $query->fetch();
	}
}