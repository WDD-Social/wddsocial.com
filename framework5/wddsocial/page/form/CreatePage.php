<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class CreatePage implements \Framework5\IExecutable {
	
	public static function execute() {
		if(!isset($_POST['type'])){
			$request = \Framework5\Request::segment(1);
			if($request != '' AND ($request == 'project' or $request == 'article' or $request == 'event' or $request == 'job')){
				$_POST['type'] = $request;
			}else{
				$_POST['type'] = 'project';	
			}
		}
		
		# display site header
		echo render(':template', array('section' => 'top', 'title' => "Create new {$_POST['type']}"));
		
		# open content section
		echo render(':section', array('section' => 'begin_content'));
		
		# display basic form header
		echo render('wddsocial.view.form.create.WDDSocial\BasicElements', array('section' => 'header', 'data' => $_POST));
		
		switch ($_POST['type']) {
			case 'project':
				echo render('wddsocial.view.form.create.WDDSocial\ProjectDetails');
				break;
		}
		
		# display form footer
		echo render('wddsocial.view.form.create.WDDSocial\BasicElements', array('section' => 'footer'));
		
		# end content section
		echo render(':section', array('section' => 'end_content'));
		
		# display site footer
		echo render(':template', array('section' => 'bottom'));
	}
}