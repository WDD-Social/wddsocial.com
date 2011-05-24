<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class RequestsPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		$requests = $this->get_requests();
		if ($requests) {
		    $content.= render('dev.view.request.Framework5\Dev\RequestsView', $requests);
		}
		
		echo render(':template',
			array('title' => 'Requests', 'content' => $content));
	}
	
	
	public function get_requests($limit = 0, $page = 0) {
		
		# get the db object
		$db = instance('core.controller.Framework5\Database');
		
		$sql = "
			SELECT log.id, time, uri, exc.request_id as exception_thrown
			FROM fw5_request_log as log
			LEFT JOIN fw5_exception_log AS exc ON (log.id = exc.request_id)
			ORDER BY id DESC
			LIMIT 100";
		$query = $db->query($sql);
		
		# setting the fetch mode
		$query->setFetchMode(\PDO::FETCH_OBJ);
		
		return $query->fetchAll();
	}
}
			