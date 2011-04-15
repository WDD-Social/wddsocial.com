<?php

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class RequestInfoPage implements \Framework5\IExecutable {
	
	public static function execute() {
		$request = \Framework5\Request::segment(1);
		
		if ($request == 'requests')
			static::_requestsListing();
		
		if ($request == 'request')
			static::_requestInfo();
		
	}
	
	
	
	private static function _requestsListing() {
		echo "{requests page}<br/>";
		
		# controller
		import('dev.controller.RequestInfo');
		$info = new RequestInfo();
		
		$requests = $info->getRequests();
		
		# showing the results
		while($row = $requests->fetch()) {
			$time = date("F j, Y, g:i a", $row->time);
			
		    echo "request id <a href=\"../request/{$row->id}\">{$row->id}</a> at $time uri [{$row->uri}] <br/>";
		}
	}
	
	
	
	private static function _requestInfo() {
		echo "{request info}<br/>";
		$request_id = \Framework5\Request::segment(2);
		
		# controller
		import('dev.controller.RequestInfo');
		$info = new RequestInfo();
		
		# check for an exception
		
		if ($exception = $info->getException($request_id))
			render('dev.view.ExceptionInfoView', $exception);
		
		# check for debug information
		$debug = $info->getDebug($request_id);
		if ($debug)
			render('dev.view.DebugInfoView', $debug);
		
	}
}