<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class DebugInfoView implements \Framework5\IView {	
	
	public function render($options = null) {
		
		# determine if sent options is correct model
		if (!get_class($options) == 'DebugInfo')
			throw new \Framework5\Exception("DebugInfoView expects parameter of type DebugInfo");
		
		# display output
		echo "<h2>Debug Data</h2>";
		$lb = "<br/>";
		
		/*
		foreach (unserialize($options->data) as $data) {
			echo "{$data->message} $lb";
			echo "{$data->file} $lb";
			echo "{$data->path} $lb";
			echo "{$data->line} $lb";
			echo "{$data->time} $lb";
			echo "{$data->memory} $lb";
		}
		*/
		
		echo '<pre>';
		print_r(unserialize($options->data));
		echo '</pre>';
	}
}