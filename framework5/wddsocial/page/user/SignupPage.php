<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SignupPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# handle form submission
		if (isset($_POST['submit'])){
			$response = $this->process_form();
			
			# auto signin user on success
			if ($response->status) {
				if (UserSession::signin($_POST['email'], $_POST['password']))
					header('Location: /');
			}
		}
		
		# display site header
		$page_title = 'Sign Up for WDD Social';
		
		# open content section
		$content = render(':section', array('section' => 'begin_content'));
		
		# display sign up form
		$content.= render('wddsocial.view.form.WDDSocial\ExtraView', 
			array('type' => 'sign_up_intro'));
		
		# display sign up form
		$content.= render('wddsocial.view.form.WDDSocial\SignupView', 
			array('error' => $response->message));
		
		# end content section
		$content.= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', 
			array('title' => $page_title, 'content' => $content));
	}
	
	
	
	/**
	* Process signup form
	*/
	
	public function process_form() {
		
		import('wddsocial.model.WDDSocial\FormResponse');
		
		# filter input variables
		//$v = filter_input(INPUT_POST, 'v', FILTER_VALIDATE_EMAIL);
		
		# check for required form values
		$required = array('terms','first-name','last-name','email','full-sail-email','password');
		$incomplete = false;
		foreach ($required as $value) {
			if ($_POST[$value] == null) $incomplete = true;
		}
		
		if ($incomplete){
			if($_POST['terms'] == null){
				return new FormResponse(false, "You must complete all required fields and agree to our <a href=\"/terms\" title=\"WDD Social Terms of Service\">Terms of Service</a>.");
			}else{
				return new FormResponse(false, "Please complete all required fields.");
			}
		}
		
		# check if user accepted terms
		if ($_POST['terms'] != 'on') {
			return new FormResponse(false, "You must agree to our <a href=\"/terms\" title=\"WDD Social Terms of Service\">Terms of Service</a>");
		}
		
		# check if user accepted terms
		if (strlen($_POST['password']) < 6) {
			return new FormResponse(false, "Your password was not long enough. Passwords must be at least 6 characters long, please try again.");
		}
		
		
		# database validation
		$db = instance(':db');
		$sel_sql = instance(':sel-sql');
		$val_sql = instance(':val-sql');
		$admin_sql = instance(':admin-sql');
		$errors = array();
		
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		if ($email) {
			# Check if email is unique
			$query = $db->prepare($val_sql->checkIfUserEmailExists);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$data = array('email' => $email);
			$query->execute($data);
			$row = $query->fetch();
			if ($row->count > 0) {
				array_push($errors, 'email');
			}
		}
		else {
			
		}
		
		$fsemail = filter_input(INPUT_POST, 'full-sail-email', FILTER_VALIDATE_EMAIL);
		if ($fsemail and (stristr($fsemail, '@fullsail.com') or stristr($fsemail, '@fullsail.edu'))) {
			# Check if Full Sail email is unique
			$query = $db->prepare($val_sql->checkIfUserFullSailEmailExists);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$data = array('fullsailEmail' => $fsemail);
			$query->execute($data);
			$row = $query->fetch();
			if ($row->count > 0) {
				array_push($errors, 'Full Sail email');
			}
		}
		else {
			return new FormResponse(false, "Your Full Sail email must be an actual Full Sail email (fullsail.edu or fullsail.com).");
		}
		
		# Display errors if information is not unique.
		if (count($errors) > 0) {
			$errorMessage = "The ";
			if (count($errors) == 1) {
				$errorMessage .= "{$errors[0]} you provided is already in use.";
			}
			else if(count($errors) == 2){
				$errors = implode(' and ',$errors);
				$errorMessage .= "$errors";
			}
			else{
				$errorMessage .=  NaturalLanguage::comma_list($errors);
			}
			$errorMessage .= " you provided are already in use. Your information must be unique.";
			return new FormResponse(false, $errorMessage);
		}
		
		# Error checking and validation complete, add user to database
		else{
			# Create vanityURL
			$vanityURL = '';
		
			# Check if vanity URL is unique, create a new one until a unique is found
			$query = $db->prepare($val_sql->checkIfUserVanityURLExists);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			
			$vanityURL = strtolower($_POST['first-name'] . $_POST['last-name']);
			for ($i = 0; $i < 100; $i++) {
				if ($i > 0) {
					$vanityURL = $vanityURL . $i;
				}
				
				$data = array('vanityURL' => $vanityURL);
				$query->execute($data);
				$row = $query->fetch();
				if ($row->count > 0) {	
					continue;
				}
				else{
					break;
				}
			}
			
			# Insert new user
			$query = $db->prepare($admin_sql->addUser);
			$data = array(
				'typeID' => $_POST['user-type'],
				'firstName' => $_POST['first-name'],
				'lastName' => $_POST['last-name'],
				'email' => $_POST['email'],
				'fullsailEmail' => $_POST['full-sail-email'],
				'password' => $_POST['password'],
				'vanityURL' => $vanityURL);
			
			# Insert user into database
			$query->execute($data);
			
			# Get new user's ID
			$userID = $db->lastInsertID();
			
			/*
# Fetch user's avatar code
			$query = $db->prepare($sel_sql->getUserAvatarByID);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$data = array('id' => $userID);
			$query->execute($data);
			$row = $query->fetch();
			$avatar = $row->avatar;
			
			if($_FILES['avatar']['error'] != 4){
				import('wddsocial.controller.processes.WDDSocial\Uploader');
				Uploader::upload_user_avatar($_FILES['avatar'],"$avatar");
			}
*/
			
			# get user verification code
			$query = $db->prepare($sel_sql->getUserVerificationCode);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$data = array('id' => $userID);
			$query->execute($data);
			$row = $query->fetch();
			
			# send verification email
			import('wddsocial.controller.WDDSocial\Mailer');
			$name = $_POST['first-name'] . $_POST['last-name'];
			$mailer = new Mailer();
			$mailer->add_recipient($name, $_POST['full-sail-email']);
			$mailer->subject = "Welcome to WDD Social";
			$mailer->message = render("wddsocial.view.email.WDDSocial\VerificationEmail", 
				array('firstName' => $_POST['first-name'], 'verificationCode' => $row->verificationCode));
			$mailer->send();
			
			return new FormResponse(true);
		}
	}
}