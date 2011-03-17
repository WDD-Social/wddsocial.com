<?php

/*
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

require_once '../php/autoload.php';

final class Application extends Framework {
	
	
	
	public function __construct() {
		
		
		# call Framework constructor
		parent::__construct();
	}
	
	public function __destruct() {
		
		
		# call Framework constructor
		parent::__destruct();
	}
		
	
	
	/**
	* Exception handler
	* 
	* @author tmatthews (tmatthewsdev@gmail.com)
	*/
	
	public function excetion_error($e) {
		
		$this->log_error($e);
		
    	# display the error page
    	/*
    	$this->import('controllers.core.Template');
		$template = new Template();
		$options = array('message' => $e->getMessage());
		$template->display('error_page', $options);
		*/
		echo 'Application:exception_error() killed execution'; //TMP
		
		# kill script execution
		die;
	}
	
	
	
	/**
	* 
	* 
	* @author tmatthews (tmatthewsdev@gmail.com)
	*/
	public function authenticate($options = null) {
		$this->import('controllers.core.Auth');
		$auth = new Auth();
		
	}
	
}