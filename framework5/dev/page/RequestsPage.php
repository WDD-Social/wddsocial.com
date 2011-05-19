<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class RequestsPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		$requests = $this->get_requests();
		while($row = $requests->fetch()) {
			$time = date("F j, Y, g:i a", $row->time);
			
		    $content.= "<a href=\"/dev/request/{$row->id}\">{$row->id}</a> at $time [<a href=\"/{$row->uri}\">/{$row->uri}</a>] <br/>";
		}
		
		echo render('dev.view.Framework5\Dev\TemplateView',
			array('title' => 'Requests', 'content' => $content));
	}
	
	
	public function get_requests($limit = 0, $page = 0) {
		
		# get the db object
		$db = instance('core.controller.Framework5\Database');
		
		$sql = "SELECT * FROM fw5_request_log ORDER BY id DESC";
		$query = $db->query($sql);
		
		# setting the fetch mode
		$query->setFetchMode(\PDO::FETCH_OBJ);
		
		return $query;
	}
}