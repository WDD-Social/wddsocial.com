<?php

namespace WDDSocial;

/*
* User signin page
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SigninPage implements \Framework5\IExecutable {
	
	/**
	* display signin page
	*/
	
	public static function execute() {
		
		# handle form submission
		if (isset($_POST['submit'])){
			static::process_form();
		}
		
		# display signin page
		else {
			# display site header
			echo render(':template', 
				array('section' => 'top', 'title' => 'Sign In to WDD Social'));
			
			# open content section
			echo render(':section', array('section' => 'begin_content'));
			
			# display sign in form
			echo render('wddsocial.view.form.WDDSocial\ExtraView', array('type' => 'sign_in_intro'));
			
			# display sign in form
			echo render('wddsocial.view.form.WDDSocial\SignInView');
			
			# end content section
			echo render(':section', 
				array('section' => 'end_content'));
			
			# display site footer
			echo render(':template', array('section' => 'bottom'));
		}
	}
	
	
	
	/**
	* Handle user signup form
	*/
	
	public static function process_form() {
						
		# filter input variables
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$password = filter_input(INPUT_POST, 'password');
		
		# validate input
		$message = false;
		if (!$password) $message = "You must enter a valid password";
		if (!$email) $message = "You must enter a valid email address";
		
		# validation and auth success
		if (!$message and UserSession::signin($email, $password)) {
			header('Location: /'); # redirect to user dashboard
		}
		
		# login failure, error page
		else {
			# if signin failed, get the error message
			if (!$message) $message = 'Incorrect username or password, please try again.';
			
			# display site header
			echo render(':template', 
				array('section' => 'top', 'title' => 'Sign In to WDD Social'));
			
			# open content section
			echo render(':section', 
				array('section' => 'begin_content'));
			
			# display sign in form
			echo render('wddsocial.view.form.WDDSocial\SigninView', 
				array('error' => $message, 'email' => $email));
						
			# end content section
			echo render(':section', 
				array('section' => 'end_content'));
			
			# display site footer
			echo render(':template', array('section' => 'bottom'));
		}
	}
}