<?php

namespace Framework5;

/*
* Application controller - default Primary Controller for Framework5
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

abstract class ApplicationBase extends Controller {	
	
	
	/**
	* Exception handler default, can be overwritten by Application
	* 
	* @param Exception e
	*/
	
	public static function exception_handler($e) {
		
		# log_error defined in Controller
		if (\Framework5\Settings::$log_exception) \Framework5\Logger::log_exception($e);
		
		# display the error page
		echo render('core.view.Framework5\ExceptionView', $e);
		
		die; # kill script execution
	}
}