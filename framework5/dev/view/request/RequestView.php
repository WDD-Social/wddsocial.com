<?php

namespace Framework5\Dev;

/**
* Renders the details of a single execution
* 
*/

class RequestView implements \Framework5\IView {	
	
	public function render($details = null) {
		
		# determine if sent options is correct model
		if (!get_class($details) == 'ExecutionDetailsView')
			throw new Framework5\Exception("ExecutionDetailsView expects parameter of type ExecutionDetails");
		
		# format data
		$details->time = Formatter::format_time($details->time);
		
		# render execution details
		$html = <<<HTML

		<p>{$details->time}</p>
		<p><a href="/{$details->uri}">/{$details->uri}</a></p>
		<p>remote address: {$details->remote_addr}</p>
HTML;
		
		# render post data
		$post_data = unserialize($details->post);
		foreach ($post_data as $key => $value) {
			$html.= <<<HTML

		<p>POST['$key'] = $value</p>	
HTML;
		}
		

		# render get data
		$get_data = unserialize($details->get);
		foreach ($get_data as $key => $value) {
			$html.= <<<HTML

		<p>GET['$key'] = $value</p>	
HTML;

		}
		
		# return output
		return $html;
	}
}