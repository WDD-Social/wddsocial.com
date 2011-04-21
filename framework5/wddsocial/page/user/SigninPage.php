<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SigninPage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		# display site header
		echo render('wddsocial.view.TemplateView', array('section' => 'top', 'title' => 'Sign In'));
		
		# open content section
		echo render('wddsocial.view.SectionView', array('section' => 'begin_content'));
		
		# display sign in form
		echo render('wddsocial.view.FormView', array('type' => 'sign_in'));
		
		# end content section
		echo render('wddsocial.view.SectionView', array('section' => 'end_content'));
		
		# display site footer
		echo render('wddsocial.view.TemplateView', array('section' => 'bottom'));
	}
}