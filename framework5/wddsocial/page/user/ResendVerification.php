<?php

namespace WDDSocial;

/*
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class ResendVerification implements \Framework5\IExecutable {
	
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
				$page = render('wddsocial.view.form.WDDSocial\ResendVerificationView', 
					array('success' => $this->successMessage));
			}
			
			# if error
			else {
				$page = render('wddsocial.view.form.WDDSocial\ResendVerificationView', 
					array('error' => $this->errorMessage));
			}
		}
		
		# form not submitted
		else {
			$page = render('wddsocial.view.form.WDDSocial\ResendVerificationView');
		}
		
		
		# display site header
		$page_title = 'Resend verification email';
		
		# open content section
		$content = render(':section', array('section' => 'begin_content'));
		
		# display signin form
		$content.= $page;
		
		# end content section
		$content.= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', 
			array('title' => $page_title, 'content' => $content));
	}
	
	
	
	public function _process_form() {
		
		# filter input variables
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
				
		# validate input
		if (!$email) {
			$this->errorMessage = "You must enter a valid address as your primary email";
			return false;
		}
		
		# check users email and password
		$db = instance(':db');
		$sel_sql = instance(':sel-sql');
		$admin_sql = instance(':admin-sql');
		
		# check if address is fullsail domain
		
		
		# check if email is valid
		$query = $db->prepare($sel_sql->getUserByFullsailEmail);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute(array('email' => $email));
		
		if ($query->rowCount() == 0) {
			$this->errorMessage = "The email address entered is invalid";
			return false;
		}
		
		# get user name and verification code
		$user = $query->fetch();
		
		# get user verification code
		$query = $db->prepare($sel_sql->getUserVerificationCode);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute(array('id' => $user->id));
		$row = $query->fetch();
		
		# resend verification email
		import('wddsocial.controller.WDDSocial\Mailer');
		$name = $_POST['first-name'] . $_POST['last-name'];
		$mailer = new Mailer();
		$mailer->add_recipient($user->firstName, $email);
		$mailer->subject = "WDD Social Email Verification";
		$mailer->message = render("wddsocial.view.email.WDDSocial\VerificationEmail", 
			array('firstName' => $user->firstName, 'verificationCode' => $row->verificationCode));
		$mailer->send();
		
		# return success message
		$this->successMessage = "A verification email has been sent to {$email}";
		return true;
		
	}
}