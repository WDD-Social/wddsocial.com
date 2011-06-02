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

		<p>
			<strong>Request {$details->id}</strong> was made to page <a href="/{$details->uri}">http://wddsocial.com/{$details->uri}</a> on {$details->time} from remote address <a href="http://www.networksolutions.com/whois/results.jsp?ip={$details->remote_addr}" title="Whois information for {$details->remote_addr}">{$details->remote_addr}</a>
		</p>
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