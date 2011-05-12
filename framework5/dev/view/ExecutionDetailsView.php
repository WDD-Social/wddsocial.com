<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class ExecutionDetailsView implements \Framework5\IView {	
	
	public function render($details = null) {
		
		# determine if sent options is correct model
		if (!get_class($details) == 'ExecutionDetailsView')
			throw new Framework5\Exception("ExecutionDetailsView expects parameter of type ExecutionDetails");
		
		echo "<p>";
		$lb = "<br/>";
		
		echo "time: {$details->time} $lb";
		echo "uri: {$details->uri} $lb";
		echo "remote address: {$details->remote_addr} $lb";
		
		echo '$_GET: ';
		print_r(unserialize($details->get));
		echo $lb;
		
		echo '$_POST: ';
		print_r(unserialize($details->post));
		echo $lb;
		echo "</p>";
	}
}