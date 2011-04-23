<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SignupPage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		if(true == false){
			static::process_form();
		}else{
			# display site header
			echo render('wddsocial.view.TemplateView', array('section' => 'top', 'title' => 'Sign Up for WDD Social'));
			
			# open content section
			echo render('wddsocial.view.SectionView', array('section' => 'begin_content'));
			
			# display sign up form
			echo render('wddsocial.view.FormView', array('type' => 'sign_up_intro'));
			
			# display sign up form
			echo render('wddsocial.view.FormView', array('type' => 'sign_up'));
			
			# end content section
			echo render('wddsocial.view.SectionView', array('section' => 'end_content'));
			
			# display site footer
			echo render('wddsocial.view.TemplateView', array('section' => 'bottom'));
		}
	}
	
	public static function process_form(){
		
	}
}