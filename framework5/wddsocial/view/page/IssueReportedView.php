<?php

namespace WDDSocial;

/*
* 
* @author 
*/

class IssueReportedView implements \Framework5\IView {
	
	public function render($error = null) {
		return <<<HTML
			<h1 class="mega">Thank you</h1>
			<p>Your issue has been reported. A gathering of magical developer gnomes will be along shortly to tidy things up.</p>
HTML;
	}
}