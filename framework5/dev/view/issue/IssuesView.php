<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class IssuesView implements \Framework5\IView {	
	
	public function render($bugs = null) {
		
		foreach ($bugs as $bug) {
			
			$html .= <<<HTML
			<a href="/dev/issues/{$bug->id}">{$bug->request_id}</a><br/>

HTML;
		}
		
		return $html;
	}
}