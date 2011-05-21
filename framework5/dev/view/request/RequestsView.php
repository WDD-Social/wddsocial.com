<?php

namespace Framework5\Dev;

/**
* Renders the details of a single execution
* 
*/

class RequestsView implements \Framework5\IView {	
	
	public function render($requests = null) {
		
		# table
		$html = <<<HTML
		<table id="requests" class="dev">
		<tr>
			<td>Request</td>
			<td>Time</td>
			<td>Request</td>
		</tr>
HTML;

		# render table rows
		foreach ($requests as $request) {
			$request->time = Formatter::format_time($request->time);
			$html.= <<<HTML

		<tr>
			<td><a href="/dev/request/{$request->id}">{$request->id}</a></td>
			<td>{$request->time}</td>
			<td><a href="/{$request->uri}">/{$request->uri}</a></td>
		</tr>
HTML;
		}

		# end table
		$html.= <<<HTML

		</table>
HTML;
		
		# return output
		return $html;
	}
}