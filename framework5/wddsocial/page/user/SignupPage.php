<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SignupPage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		if(isset($_POST['process']) && $_POST['process'] == 'signup'){
			static::process_form();
		}else{
			# display site header
			echo render('wddsocial.view.WDDSocial\TemplateView', 
				array('section' => 'top', 'title' => 'Sign Up for WDD Social'));
			
			# open content section
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
			
			# display sign up form
			echo render('wddsocial.view.WDDSocial\FormView', array('type' => 'sign_up_intro'));
			
			# display sign up form
			echo render('wddsocial.view.WDDSocial\FormView', array('type' => 'sign_up'));
			
			# end content section
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'end_content'));
			
			# display site footer
			echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'bottom'));
		}
	}
	
	public static function process_form(){
		import('wddsocial.controller.WDDSocial\Uploader');
		\WDDSocial\Uploader::upload_user_avatar($_FILES['avatar'],'test');
		echo "<pre>";
		print_r($_POST);
		print_r($_FILES);
		echo "</pre>";
	}
}