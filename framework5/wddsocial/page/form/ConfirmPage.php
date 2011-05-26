<?php

namespace WDDSocial;

/*
* 
* Author: Anthony Colangelo (me@acolangelo.com)
*/

class ConfirmPage implements \Framework5\IExecutable {
	
	public function __construct() {
		
	}
	
	public function execute() {
		if (UserSession::is_authorized()) redirect('/');
		
		# open content section
		$content = render(':section', array('section' => 'begin_content'));
		
		switch (\Framework5\Request::segment(1)) {
			case 'createjob':
				$content .= render('wddsocial.view.confirmation.WDDSocial\ConfirmCreateJob');
				break;
			case 'editjob':
				$content .= render('wddsocial.view.confirmation.WDDSocial\ConfirmEditJob');
				break;
			case 'deletejob':
				$content .= render('wddsocial.view.confirmation.WDDSocial\ConfirmDeleteJob');
				break;
		}
		
		# end content section
		$content .= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', array('title' => 'Post a Job', 'content' => $content));
	}
}