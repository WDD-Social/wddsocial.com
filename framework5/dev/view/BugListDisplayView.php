<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class BugListDisplayView implements \Framework5\IView {	
	
	public function render($bugs = null) {
		
		foreach ($bugs as $bug) {
			echo "for {$bug->request_id}";
			
			$html .= <<<HTML
			<a href="/dev/bug/{$bug->id}">{$bug->id}</a>

HTML;
		}
		
		return $html;
	}
}