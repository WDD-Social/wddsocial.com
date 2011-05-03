<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SignInPage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		# handle form submission
		if (isset($_POST['process']) && $_POST['process'] == 'signin'){
			static::process_form();
		}
		
		# display signin page
		else {
			# display site header
			echo render('wddsocial.view.WDDSocial\TemplateView', 
				array('section' => 'top', 'title' => 'Sign In to WDD Social'));
			
			# open content section
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
			
			# display sign in form
			echo render('wddsocial.view.form.WDDSocial\ExtraView', array('type' => 'sign_in_intro'));
			
			# display sign in form
			echo render('wddsocial.view.form.WDDSocial\SignInView');
			
			# end content section
			echo render('wddsocial.view.WDDSocial\SectionView', 
				array('section' => 'end_content'));
			
			# display site footer
			echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'bottom'));
		}
	}
	
	
	
	public static function process_form(){
		
		UserSession::fake_user_signin(2);
		header('Location: /');
		return true;
		
		$success = true;
				
		# filter input variables
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$password = filter_input(INPUT_POST, 'password');
		
		if (!$password) {
			$message = "You must enter a valid password";
			$success = false;
		}
		
		if (!$email) {
			$message = "You must enter a valid email address";
			$success = false;
		}
		
		# validation and auth success
		if ($success and UserSession::signin($email, $password)) {
			header('Location: /'); # redirect to user dashboard
		}
		
		# login failure, error page
		else {
			# if signin failed, get the error message
			if ($success) $message = UserSession::error_message();
			
			# display site header
			echo render('wddsocial.view.WDDSocial\TemplateView', 
				array('section' => 'top', 'title' => 'Sign In to WDD Social'));
			
			# open content section
			echo render('wddsocial.view.WDDSocial\SectionView', 
				array('section' => 'begin_content'));
			
			# display sign in form
			echo render('wddsocial.view.form.WDDSocial\SigninView', 
				array('error' => $message, 'email' => $email));
						
			# end content section
			echo render('wddsocial.view.WDDSocial\SectionView', 
				array('section' => 'end_content'));
			
			# display site footer
			echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'bottom'));
		}
	}
}