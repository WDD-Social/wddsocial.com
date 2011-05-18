<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class BugInfoView implements \Framework5\IView {	
	
	public function render($bug = null) {
		
		if (!set($bug))
			throw new Exception("BugInfoView requires a bug to display");
		
		# output
		return <<<HTML
			<a href="/dev/request/{$bug->request_id}">Request ID: {$bug->request_id}</a><br/>
			<p>User ID: {$bug->user_id}</p>
			<p>Message: {$bug->message}</p>

HTML;
	}
}