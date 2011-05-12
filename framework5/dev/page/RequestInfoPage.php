<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class RequestInfoPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# get request id from url
		$request_id = \Framework5\Request::segment(2);
		
		#TODO move to view
		echo "<h2>Request $request_id</h2><br/>";
		
		# data controller
		import('dev.controller.Framework5\Dev\RequestInfo');
		$info = new RequestInfo($request_id);
		
		# check for request details
		if ($details = $info->get_details())
			render('dev.view.Framework5\Dev\ExecutionDetailsView', $details);
		
		# check for execution information
		if ($execution = $info->get_execution())
			render('dev.view.Framework5\Dev\ExecutionInfoView', $execution);
		
		# check for trace data
		//$trace = $info->get_trace();
		if ($trace = $info->get_trace())
			echo render('dev.view.Framework5\Dev\TraceInfoView', $trace);
		
		# check for an exception
		if ($exception = $info->get_exception())
			render('dev.view.Framework5\Dev\ExceptionInfoView', $exception);
		
		# check for debug information
		if ($debug = $info->get_debug())
			render('dev.view.Framework5\Dev\DebugInfoView', $debug);
		
	}
}