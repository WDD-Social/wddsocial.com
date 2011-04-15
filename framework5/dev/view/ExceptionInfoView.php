<?php

class ExceptionInfoView implements \Framework5\IView {	
	
	public static function render($options = null) {
		
		# determine if sent options is correct model
		if (!get_class($options) == 'ExceptionInfo')
			throw new Framework5\Exception("ExceptionInfoView expects parameter of type ExceptionInfo");
		
		# display output
		echo "{an exception was caught}";
		echo $lb = '<br/>';
		echo $options->request_id . $lb;
		echo $options->type . $lb;
		$time = date("F j, Y, g:i a", $options->timestamp);
		echo $time . $lb;
		echo $options->message . $lb;
		echo $options->code . $lb;
		echo $options->file . $lb;
		echo $options->line . $lb;
		echo '<pre>';
		print_r(unserialize($options->trace));
		echo '</pre>';
	}
}