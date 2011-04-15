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
		
		#TODO display the error page
		//$options = array('exception' => $e)
		//display('core.view.ErrorPage', $options);
		echo "<pre>";
		echo "ApplicationBase::exception_error() killed execution with message: {$e->getMessage()}<br/>"; //TMP
		echo "thrown in {$e->getFile()} on line {$e->getLine()}<br/>";
		echo "Exception->getTrace()</br>";
		print_r($e->getTrace());
		echo "</pre>";
		die; # kill script execution
	}
	
	
	
	/**
	* TODO
	* 
	* @author tmatthews (tmatthewsdev@gmail.com)
	*/
	/*
	public static function shutdown_handler() {		
		$error = error_get_last();
		if($error['type'] == 1){
			// type, message, file, line
			echo "ERROR shutdown_handler";
		}
	}
	
	public static function error_handler($number, $message, $file, $line, $context) {
		if (!(error_reporting() & $number)) return;
		
		switch ($number) {
			case E_USER_ERROR:
				echo "<b>E_USER_ERROR</b> number: $number message: $message<br/>\n";
				echo "Fatal error on line $line in file $file<br/>\n";
				echo "context: $context";
				exit(1);
				break;

			case E_USER_WARNING:
				echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
				break;

			case E_USER_NOTICE:
				echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
				break;

			default:
				echo "Unknown error type: [$errno] $errstr<br />\n";
				break;
		}
		
		return true; # disable php default error message
	}	
	*/
	
}