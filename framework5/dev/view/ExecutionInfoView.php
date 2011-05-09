<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class ExecutionInfoView implements \Framework5\IView {	
	
	public function render($options = null) {
		
		# determine if sent options is correct model
		if (!get_class($options) == 'ExecutionInfo')
			throw new Framework5\Exception("ExecutionInfoView expects parameter of type ExecutionInfo");
		
		echo "<h2>Execution Info</h2>";
		$lb = "<br/>";
		
		echo "start time: {$options->start_time} $lb";
		echo "execution time: {$options->exec_time} $lb";
		echo "memory peak: {$options->memory_peak} $lb";
		
	}
}