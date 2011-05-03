<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class AccountPage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		# display site header
		echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'top', 'title' => 'My Account'));
			
		# open content section
		echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
		
		# display account form
		echo render('wddsocial.view.form.WDDSocial\AccountView', array('user' => $_SESSION['user']));
			
		# end content section
		echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'end_content'));
		
		# display site footer
		echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'bottom'));
		
		echo "<pre>";
		print_r($_SESSION['user']);
		echo "</pre>";
	}
	
	
	
	public static function process_form(){
		
	}
}