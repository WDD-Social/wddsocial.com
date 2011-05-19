<?php

namespace WDDSocial;

/*
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class ChangeFullsailEmail implements \Framework5\IExecutable {
	
	/**
	* 
	*/
	
	public function execute() {
		
		# handle form submission
		if (isset($_POST['submit'])){
			
			# handle form submit
			$response = $this->_process_form();
			
			# if success
			if ($response) {
				$page = render('wddsocial.view.form.WDDSocial\ChangeFullsailEmailView', 
					array('success' => $this->successMessage));
			}
			
			# if error
			else {
				$page = render('wddsocial.view.form.WDDSocial\ChangeFullsailEmailView', 
					array('error' => $this->errorMessage));
			}
		}
		
		# form not submitted
		else {
			$page = render('wddsocial.view.form.WDDSocial\ChangeFullsailEmailView');
		}
		
		
		# open content section
		$content = render(':section', array('section' => 'begin_content'));
		
		# display sign in form header
		//$content.= render('wddsocial.view.form.WDDSocial\ExtraView', array('type' => 'sign_in_intro'));
		
		# add page content
		$content.= $page;
		
		# end content section
		$content.= render(':section', array('section' => 'end_content'));
		
		# display page
		$page_title = 'Change Full Sail email address';
		echo render(':template', 
			array('title' => $page_title, 'content' => $content));
	}
	
	
	
	private function _process_form() {
		
		$db = instance(':db');
		$sel_sql = instance(':sel-sql');
		$admin_sql = instance(':admin-sql');
		
		# filter input variables
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$fs_email = filter_input(INPUT_POST, 'fs-email', FILTER_VALIDATE_EMAIL);
		$password = filter_input(INPUT_POST, 'password');
		
		# validate input
		if (!$email) {
			$this->errorMessage = "You must enter a valid address as your primary email";
			return false;
		}
		if (!$fs_email) {
			$this->errorMessage = "You must enter a valid address as your Full Sail email";
			return false;
		}
		if (!$password) {
			$this->errorMessage = "You must enter your password";
			return false;
		}
		
		# validate address is fullsail domain
		
		
		
		# check users email and password
		$query = $db->prepare($sel_sql->changeFullsailEmailInfo);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute(array('email' => $email, 'password' => $password));
		
		if ($query->rowCount() == 0) {
			$this->errorMessage = "Your primary email or password was incorrect";
			return false;
		}
		
		# set user properties
		$user = $query->fetch();
		
		# check if user is already verified
		if ($user->verified == 1) {
			$this->errorMessage = "This account has already been verified. You can edit your Full Sail address on the Account page after signin.";
			return false;
		}
		
		
		# update fullsail address
		$query = $db->prepare($admin_sql->updateUserFullsailEmail);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute(array('id' => $user->id, 'fullsailEmail' => $fs_email));
		
		
		# resend verification email
		import('wddsocial.controller.WDDSocial\Mailer');
		$name = $_POST['first-name'] . $_POST['last-name'];
		$mailer = new Mailer();
		$mailer->add_recipient($user->firstName, $fs_email);
		$mailer->subject = "WDD Social Email Verification";
		$mailer->message = render("wddsocial.view.email.WDDSocial\VerificationEmail", 
			array('firstName' => $user->firstName, 'verificationCode' => $user->verificationCode));
		$mailer->send();
		
		
		# return success message
		$this->successMessage = "Your Full Sail email address changed. A verification email has been sent to that address.";
		return true;
		
	}
}