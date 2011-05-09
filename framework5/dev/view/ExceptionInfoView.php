<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class ExceptionInfoView implements \Framework5\IView {	
	
	public function render($options = null) {
		
		# determine if sent options is correct model
		if (!get_class($options) == 'ExceptionInfo')
			throw new Framework5\Exception("ExceptionInfoView expects parameter of type ExceptionInfo");
		
		# display output
		echo "<h2>an exception was caught</h2>";
		echo $lb = '<br/>';
		echo "Request ID: {$options->request_id} $lb";
		echo "Exception type: {$options->type} $lb";
		$time = date("F j, Y, g:i a", $options->timestamp);
		echo "Time: $time $lb";
		echo "Message: {$options->message} $lb";
		echo "Code: {$options->code} $lb";
		echo "File: {$options->file} $lb";
		echo "Line: {$options->line} $lb";
		
		echo "<h3>Backtrace</h3>";
		echo '<pre>';
		print_r(unserialize($options->trace));
		echo '</pre>';
	}
}