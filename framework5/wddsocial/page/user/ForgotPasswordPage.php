<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class ForgotPasswordPage implements \Framework5\IExecutable {
	
	public function __construct() {
		//$this->lang = new \Framework5\Lang('wddsocial.lang');
		$this->db = instance(':db');
	}
	
	
	
	public function execute() {

		if (isset($_POST['submit'])) {		
			
			# form success
			if ($this->_process_form()) {
				$intro = "A link has been sent to your email address to reset your password.";
				$form = render('wddsocial.view.form.WDDSocial\ForgotPasswordView', 
					array('section' => 'top', 'title' => 'Forgot Password', 'intro' => $intro));
			}
			
			# form failure
			else {
				$form = render('wddsocial.view.form.WDDSocial\ForgotPasswordView', 
					array('section' => 'top', 'title' => 'Forgot Password', 'error' => $this->errorMsg));
			}
		}
		
		
		else {
			$intro = "Have you forgot your password? Enter one of the addresses entered during signup and we'll email you a link to reset your password.";
			$form = render('wddsocial.view.form.WDDSocial\ForgotPasswordView', 
					array('section' => 'top', 'title' => 'Forgot Password', 'intro' => $intro));
		}
		
		
		
		# display site header
		echo render(':template', array('section' => 'top', 'title' => 'Forgot Password'));
		
		# open content section
		echo render(':section', array('section' => 'begin_content'));
		
		# display form header
		echo render('wddsocial.view.form.WDDSocial\ExtraView', array('type' => 'forgot_pass_intro'));
		
		# 
		echo $form;
		
		# end content section
		echo render(':section', array('section' => 'end_content'));
		
		# display site footer
		echo render(':template', array('section' => 'bottom'));
	}
	
	
	
	/**
	* 
	* 
	*/
	
	private function _process_form() {
		
		# validate input
		if (!isset($_POST['email']))
			return false;
		
		# validate user input
		$email = $_POST['email'];
		
		# check to see if user email is valid
		$query = $this->db->prepare("
			SELECT id, firstName, lastName
			FROM users
			WHERE email = :email OR fullsailEmail = :email
			LIMIT 1");
		
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$data = array('email' => $email);
		$query->execute($data);
		
		# invalid email
		if ($query->rowCount() == 0) {
			$this->errorMsg = 'The email address entered is not registered';
			return false;
		}
		
		while ($user = $query->fetch()) {
			$user_id = $user->id;
			$name = "{$user->firstName} {$user->lastName}";
		}
		
		# generate password reset code
		$pass_code = substr(md5(time() + 'h5so3hdj8h'), 0, 16);
		$link = "http://wddsocial.com/new-password/{$pass_code}";
		
		# add code to database
		$query = $this->db->prepare("
			UPDATE users
			SET passwordCode = :passwordCode
			WHERE id = :id;");
		
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$data = array('id' => $user_id, 'passwordCode' => $pass_code);
		$query->execute($data);
		
		# email password to user
		import('wddsocial.controller.WDDSocial\Mailer');
		$mailer = new Mailer();
		$mailer->add_recipient($name, $email);
		$mailer->subject = "WDD Social Password Reset";
		$mailer->message = "<p>Click the link to set a new password.</p><a href=\"$link\">$link</a>";
		$mailer->send();
		
		return true;
	}
}