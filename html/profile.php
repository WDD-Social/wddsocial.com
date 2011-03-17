<?php

/**
* Sample site page
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/


try {
	# primary Application controller
	require_once 'Application.php';
	$app = new Application();
	
	# core Template controller
	$app->import('core.controller.Template');
	$template = new Template();
	
	# site Profile controller
	$app->import('site.controller.Profile');
	$profile = new Profile();
	
	# try something that will throw and error
	//$profile->is_even(1);
	
}
catch (AppException $e) {
	echo "Caught Application Exception: {$e->getMessage()}";
	die;
	
}
catch (Exception $e) {
	//$app->excetion_error($e);
	echo "Caught Exception: {$e->getMessage()}";
}



# display page
//$template->display('doc_header', array('title' => 'wddsocial'));

//$template->display('header');

//$template->display('footer');