<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class IssuesView implements \Framework5\IView {	
	
	public function render($bugs = null) {
		
		# table head
		$html = <<<HTML

			<table id="issues" class="dev">
				<tr>
					<td>Issue</td>
					<td>Time</td>
					<td>Message</td>
				</tr>
HTML;

		# table rows
		foreach ($bugs as $bug) {
			
			# limit characters
			$bug->message = substr($bug->message, 0, 50);
			$html.= <<<HTML
				<tr>
					<td><a href="/dev/issues/{$bug->id}">Issue {$bug->id}</a></td>
					<td>{$bug->}</td>
					<td>{$bug->message}</td>
				</tr>

HTML;
		}
		
		# table end
		$html.= <<<HTML

			</table>
HTML;
		
		return $html;
	}
}