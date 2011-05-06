<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class CreatePage implements \Framework5\IExecutable {
	
	public static function execute() {
		UserSession::protect();
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
		
		# display content type-specific options
		if ($_POST['type'] == 'project' || $_POST['type'] == 'article' || $_POST['type'] == 'event' || $_POST['type'] == 'job') {
			$typeCapitalized = ucfirst($_POST['type']);
			echo render("wddsocial.view.form.create.WDDSocial\\{$typeCapitalized}ExtraInputs");
		}
		
		# display team member section for appropriate content types
		if ($_POST['type'] == 'project' || $_POST['type'] == 'article') {
			switch ($_POST['type']) {
				case 'project':
					$teamTitle = 'Team Members';
					break;
				case 'article':
					$teamTitle = 'Authors';
					break;
			}
			echo render('wddsocial.view.form.pieces.WDDSocial\TeamMemberInputs', array('header' => $teamTitle, 'type' => $_POST['type']));
		}
		
		# display image section
		echo render('wddsocial.view.form.pieces.WDDSocial\ImageInputs');
		
		# display video section
		echo render('wddsocial.view.form.pieces.WDDSocial\VideoInputs');
		
		# display category section
		echo render('wddsocial.view.form.pieces.WDDSocial\CategoryInputs');
		
		# display link section
		echo render('wddsocial.view.form.pieces.WDDSocial\LinkInputs');
		
		#display course section
		if ($_POST['type'] != 'job') {
			echo render('wddsocial.view.form.pieces.WDDSocial\CourseInputs');
		}
		
		# display other options
		echo render('wddsocial.view.form.pieces.WDDSocial\OtherInputs', $_POST);
		
		# display form footer
		echo render('wddsocial.view.form.create.WDDSocial\BasicElements', array('section' => 'footer'));
		
		# end content section
		echo render(':section', array('section' => 'end_content'));
		
		# display site footer
		echo render(':template', array('section' => 'bottom'));
	}
}