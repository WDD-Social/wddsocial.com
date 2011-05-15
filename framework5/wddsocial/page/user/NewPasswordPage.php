<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class NewPasswordPage implements \Framework5\IExecutable {
	
	public function __construct() {
		//$this->lang = new \Framework5\Lang('wddsocial.lang');
		$this->db = instance(':db');
	}
	
	
	
	public function execute() {
		
		# check verification code
		$this->code  = \Framework5\Request::segment(1);
		$valid = $this->_verify_code($this->code);
		
		# display site header
		echo render(':template', array('section' => 'top', 'title' => 'Create New Password'));
		echo render(':section', array('section' => 'begin_content'));
		
		# display page header
		echo render('wddsocial.view.form.WDDSocial\ExtraView', array('type' => 'new_pass_intro'));
		
		# display page content
		if ($valid) echo $this->_display_form_content();
		else echo $this->_display_invalid_message();
		
		# display site footer
		echo render(':section', array('section' => 'end_content'));
		echo render(':template', array('section' => 'bottom'));
	}
	
	
	
	/**
	* 
	*/
	
	private function _display_form_content() {
		
		if (isset($_POST['submit'])) {
			# form success
			if ($this->_process_form()) {
				$message = "Password changed. You can now <a href=\"/signin\">signin</a>.";
				return render('wddsocial.view.form.WDDSocial\NewPasswordSuccessView', $message);
			}
			
			# form failure, show error
			else {
				return render('wddsocial.view.form.WDDSocial\NewPasswordView', 
					array('error' => $this->errorMsg));
			}
		}
		
		else { # !isset($_POST['submit'])
			$intro = "Enter your email address and a new password to signin with";
			return render('wddsocial.view.form.WDDSocial\NewPasswordView', array('intro' => $intro, 'code' => $this->code));
		}
	}
	
	
	
	/**
	* 
	*/
	
	private function _display_invalid_message() {
		# if the code isnt valid
		$message = "The new password verification code is incorrect";
		return render('wddsocial.view.form.WDDSocial\NewPasswordErrorView', $message);
		
	}
	
	
	
	/**
	* 
	*/
	
	private function _process_form() {
		
		$email    = $_POST['email'];
		$password = md5($_POST['password']);
		
		# update users password in database
		$query = $this->db->prepare("
			UPDATE users
			SET password = :password, passwordCode = NULL
			WHERE passwordCode = :passwordCode;");
		
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$data = array('password' => $password, 'passwordCode' => $this->code);
		$query->execute($data);
		
		return true;
	}
	
	
	
	/**
	* 
	*/
	
	private function _verify_code($code) {
		$query = $this->db->prepare("
			SELECT id, email
			FROM users
			WHERE passwordCode = :passwordCode
			LIMIT 1");
		
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$data = array('passwordCode' => $code);
		$query->execute($data);
		
		if ($query->rowCount() == 0) {
			$this->errorMsg = 'Invalid code';
			return false;
		}
		
		while ($user = $query->fetch()) {
			$user_id = $user->id;
			$email = $user->email;
		}
		
		return true;
	}
}