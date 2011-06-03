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
		$this->sel = instance(':sel-sql');
		$this->admin = instance(':admin-sql');
	}
	
	
	
	public function execute() {
		
		# check verification code
		$this->code  = \Framework5\Request::segment(1);
		$valid = $this->_verify_code($this->code);
		
		# display site header
		$page_title = 'Create New Password';
		
		$content = render(':section', array('section' => 'begin_content'));
		
		# display page header
		$content.= render('wddsocial.view.form.WDDSocial\ExtraView', 
			array('type' => 'new_pass_intro'));
		
		# display page content
		if ($valid) $content.= $this->_display_form_content();
		else $content.= $this->_display_invalid_message();
		
		# display site footer
		$content.= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', 
			array('title' => $page_title, 'content' => $content));
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
		$password = $_POST['password'];
		
		# update users password in database
		$query = $this->db->prepare($this->admin->resetPassword);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute(array('password' => $password, 'passwordCode' => $this->code, 'salt' => Hash::$salt));
		
		return true;
	}
	
	
	
	/**
	* 
	*/
	
	private function _verify_code($code) {
		$query = $this->db->prepare($this->sel->getUserByPasswordCode);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute(array('passwordCode' => $code));
		
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