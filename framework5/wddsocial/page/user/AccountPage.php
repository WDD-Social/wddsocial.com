<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class AccountPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# display site header
		echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'top', 'title' => 'My Account'));
			
		# open content section
		echo render(':section', array('section' => 'begin_content'));
		
		# display account form
		echo render('wddsocial.view.form.WDDSocial\AccountView', array('user' => $_SESSION['user']));
			
		# end content section
		echo render(':section', array('section' => 'end_content'));
		
		# display site footer
		echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'bottom'));
	}
	
	
	
	private function _process_form(){
		
	}
}