<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SpamPage implements \Framework5\IExecutable {

	public function execute() {
		
		$requests = $this->get_requests();
		
		foreach ($requests as $request) {
			$content.= <<<HTML

				<a href="http://www.networksolutions.com/whois/results.jsp?ip={$request->remote_addr}">{$request->remote_addr}</a><br/>
HTML;
		}
		
		/*if ($requests) {
		    $content.= render('dev.view.request.Framework5\Dev\RequestsView', $requests);
		}*/
		
		echo render(':template', 
			array('title' => "Spam Page", 'content' => $content));
	}
	
	
	
	public function get_requests($limit = 0, $page = 0) {
		
		# get the db object
		$db = instance('core.controller.Framework5\Database');
		
		$sql = "
			SELECT id, uri, remote_addr, TIME(FROM_UNIXTIME(`time`)), post
			FROM fw5_request_log
			WHERE uri = 'signup'
			ORDER BY id DESC";
		
		$query = $db->query($sql);
		
		# setting the fetch mode
		$query->setFetchMode(\PDO::FETCH_OBJ);
		
		return $query->fetchAll();
	}
}