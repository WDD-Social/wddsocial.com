<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class ExecutionDetailsView implements \Framework5\IView {	
	
	public function render($options = null) {
		
		# determine if sent options is correct model
		if (!get_class($options) == 'ExecutionDetailsView')
			throw new Framework5\Exception("ExecutionDetailsView expects parameter of type ExecutionDetails");
		
		echo "<p>";
		$lb = "<br/>";
		
		echo "time: {$options->time} $lb";
		echo "uri: {$options->uri} $lb";
		echo "</p>";
	}
}