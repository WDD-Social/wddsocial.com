<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class RequestInfoPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# import dependencies
		import('dev.controller.Framework5\Dev\BytesConverter');
		
		# get request id from url
		$request_id = \Framework5\Request::segment(2);
		
		# data controller
		import('dev.controller.Framework5\Dev\RequestInfo');
		$info = new RequestInfo($request_id);
		
		
		# check for request details
		if ($request = $info->get_request())
			$output.= render('dev.view.request.Framework5\Dev\RequestView', $request);
		
		# check for execution information
		if ($execution = $info->get_execution())
			$output.= render('dev.view.request.Framework5\Dev\ExecutionView', $execution);
		
		# check for trace data
		if ($trace = $info->get_trace())
			$output.= render('dev.view.request.Framework5\Dev\TraceView', $trace);
		
		# check for an exception
		if ($exception = $info->get_exception())
			$output.= render('dev.view.request.Framework5\Dev\ExceptionView', $exception);
		
		# check for debug information
		if ($debug = $info->get_debug())
			$output.= render('dev.view.request.Framework5\Dev\DebugView', $debug);
		
		# display page
		echo render(':template', 
			array('content' => $output, 'title' => 'Request Info'));
	}
}