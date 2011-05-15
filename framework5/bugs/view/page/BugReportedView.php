<?php

namespace Framework5\Bugs;

/*
* 
* @author 
*/

class BugReportedView implements \Framework5\IView {
	
	public function render($error = null) {
		return <<<HTML
			<h1>Thank you</h1>
			<p>Your issue has been posted</p>
HTML;
	}
}