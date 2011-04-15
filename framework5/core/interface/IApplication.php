<?php

namespace Framework5;

/*
* Framework5 Application Interface
*/

interface IApplication extends IExecutable {
	
	public static function exception_handler($e);
	//public static function shutdown_handler();
	//public static function error_handler($number, $message, $file, $line, $context);
}

?>