<?php

try {
	## include and instantiate required resources
	
	# primary Application controller
	require_once '../Application.php';
	$app = new Application();
	
	# core Template controller
	$app->import('controllers.core.Template');
	$template = new Template();
	
	# site Profile controller
	$app->import('controllers.site.Profile');
	$profile = new Profile();
	
	#TMP try something that will fail
	//$profile->is_even(1);
	
	$db = $app->instance('db');
	echo $db->hello();
	
}
catch (AppException $e) {
	echo "Caught Application Exception: {$e->getMessage()}";
	die;
	
}
catch (Exception $e) {
	//$app->excetion_error($e);
	echo $e->getMessage();
}




# display page
//$template->display('doc_header', array('title' => 'wddsocial'));

//$template->display('header');

//$template->display('footer');