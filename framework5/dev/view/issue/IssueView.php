<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class IssueView implements \Framework5\IView {	
	
	public function render($bug = null) {
		
		if (!set($bug))
			throw new Exception("BugInfoView requires a bug to display");
		
		# output
		return <<<HTML
			<h1>Issue {$bug->id}</h1>
			<p>Request ID: <a href="/dev/request/{$bug->request_id}">{$bug->request_id}</a></p>
			<p>User ID: {$bug->user_id}</p>
			<p>Message:<br/>{$bug->message}</p>

HTML;
	}
}