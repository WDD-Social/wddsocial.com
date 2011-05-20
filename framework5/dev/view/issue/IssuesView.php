<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class IssuesView implements \Framework5\IView {	
	
	public function render($bugs = null) {
		
		$html = <<<HTML

			<table id="issues" class="dev">
				<tr>
					<td>Issue</td>
					<td>Message</td>
				</tr>
HTML;
		
		foreach ($bugs as $bug) {
			$short_message = substr($bug->message, 0, 50);
			
			$html.= <<<HTML
				<tr>
					<td><a href="/dev/issues/{$bug->id}">Issue {$bug->id}</a></td>
					<td>{$short_message}</td>
				</tr>

HTML;
		}
		
		$html.= <<<HTML

			</table>
HTML;
		
		return $html;
	}
}