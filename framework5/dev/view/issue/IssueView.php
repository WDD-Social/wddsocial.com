<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class IssueView implements \Framework5\IView {	
	
	public function render($bug = null) {
		
		# validate input
		if (!set($bug))
			throw new Exception("BugInfoView requires a bug to display");
		
		# format output
		$bug->user = $this->user_info($bug->user_id);
		$bug->timestamp = Formatter::format_time($bug->timestamp);
		
		# output
		return <<<HTML

			<p><strong>Issue {$bug->id}</strong> was submitted by user <a href="http://wddsocial.com/user/{$bug->user->vanityURL}">{$bug->user->firstName} {$bug->user->lastName}</a> on {$bug->timestamp}  request id <a href="/dev/request/{$bug->request_id}">{$bug->request_id}</a></p>
			
			<p><strong>Message:</strong> {$bug->message}</p>
HTML;
	}
	
	
	
	private function user_info($id) {
		
		$db = instance('wddsocial.controller.WDDSocial\Database');
		$sql = instance('wddsocial.sql.WDDSocial\SelectorSQL');
		
		$query = $db->prepare($sql->getUserByID);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		
		$query->execute(array('id' => $id));
				
		if ($query->rowCount() > 0)
			return $query->fetch();
		else
			return "anonymous";
	}
}