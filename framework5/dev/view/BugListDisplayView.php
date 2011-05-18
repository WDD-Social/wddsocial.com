<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class BugListDisplayView implements \Framework5\IView {	
	
	public function render($bugs = null) {
		
		foreach ($bugs as $bug) {
			
			$html .= <<<HTML
			<a href="/dev/bug/{$bug->id}">{$bug->request_id}</a>

HTML;
		}
		
		return $html;
	}
}