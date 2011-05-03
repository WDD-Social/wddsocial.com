<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SignupPage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		if(isset($_POST['submit'])){
			static::process_form();
		}else{
			static::display_form();
		}
	}
	
	
	
	private static function display_form($error = '', $data = array()){
		echo render('wddsocial.view.WDDSocial\TemplateView', 
				array('section' => 'top', 'title' => 'Sign Up for WDD Social'));
			
		# open content section
		echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
		
		# display sign up form
		echo render('wddsocial.view.form.WDDSocial\ExtraView', array('type' => 'sign_up_intro'));
		
		# display sign up form
		echo render('wddsocial.view.form.WDDSocial\SignUpView', array('error' => $error, 'data' => $data));
		
		# end content section
		echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'end_content'));
		
		# display site footer
		echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'bottom'));
	}
	
	
	
	public static function process_form(){
		$required = array('terms','first-name','last-name','email','full-sail-email','password');
		$incomplete = false;
		foreach($required as $value){
			if($_POST[$value] == NULL)
				$incomplete = true;
		}
		if($incomplete){
			if($_POST['terms'] == NULL){
				static::display_form("You must agree to our <a href=\"{$root}terms\" title=\"WDD Social Terms of Service\">Terms of Service</a>, and complete all required fields.",$_POST);
			}else{
				static::display_form("Please complete all required fields.",$_POST);
			}
		}else{
			# Get db instance and query
			$db = instance(':db');
			$sel_sql = instance(':sel-sql');
			$val_sql = instance(':val-sql');
			$admin_sql = instance(':admin-sql');
			
			$errors = array();
			
			# Check if email is unique
			$query = $db->prepare($val_sql->checkIfEmailExists);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$data = array('email' => $_POST['email']);
			$query->execute($data);
			$row = $query->fetch();
			if($row->count > 0){
				array_push($errors, 'email');
			}
			
			# Check if Full Sail email is unique
			$query = $db->prepare($val_sql->checkIfFullSailEmailExists);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$data = array('fullsailEmail' => $_POST['full-sail-email']);
			$query->execute($data);
			$row = $query->fetch();
			if($row->count > 0){
				array_push($errors, 'Full Sail email');
			}
			
			# Display errors if information is not unique.
			if(count($errors) > 0){
				$errorMessage = "The ";
				if(count($errors) == 1){
					$errorMessage .= "{$errors[0]} you provided is already in use.";
				}else if(count($errors) == 2){
					$errors = implode(' and ',$errors);
					$errorMessage .= "$errors";
				}else{
					$errorMessage .=  \WDDSocial\NaturalLanguage::comma_list($errors);
				}
				$errorMessage .= " you provided are already in use. Your information must be unique.";
				static::display_form($errorMessage);
			}else{
				
				# Error checking and validation complete, add user to database 
				
				# Create vanityURL
				$vanityURL = '';
			
				# Check if vanity URL is unique, create a new one until a unique is found
				$query = $db->prepare($val_sql->checkIfVanityURLExists);
				$query->setFetchMode(\PDO::FETCH_OBJ);
				
				for($i = 0; $i < 100; $i++){
					if($i < 1){
						$vanityURL = $_POST['first-name'] . $_POST['last-name'];
					}else{
						$vanityURL = $_POST['first-name'] . $_POST['last-name'] . $i;
					}
					$vanityURL = strtolower($vanityURL);
					$data = array('vanityURL' => $vanityURL);
					$query->execute($data);
					$row = $query->fetch();
					if($row->count > 0){	
						continue;
					}else{
						break;
					}
				}
				
				# Get user type ID by title
				$query = $db->prepare($sel_sql->getUserTypeIDByTitle);
				$query->setFetchMode(\PDO::FETCH_OBJ);
				$data = array('title' => $_POST['user-type']);
				$query->execute($data);
				$row = $query->fetch();
				$typeID = $row->id;
				
				# Insert new user
				$query = $db->prepare($admin_sql->addUser);
				$data = array(
					'typeID' => $typeID,
					'firstName' => $_POST['first-name'],
					'lastName' => $_POST['last-name'],
					'email' => $_POST['email'],
					'fullsailEmail' => $_POST['full-sail-email'],
					'password' => $_POST['password'],
					'vanityURL' => $vanityURL,
					'bio' => $_POST['bio'],
					'hometown' => $_POST['hometown'],
					'birthday' => $_POST['birthday']
				);
				# Insert user into database
				$query->execute($data);
				
				# Get new user's ID
				$userID = $db->lastInsertID();
				
				# Create user's avatar code
				$query = $db->prepare($admin_sql->addUserAvatar);
				$data = array('id' => $userID, 'avatar' => "user{$userID}");
				$query->execute($data);
				
				# Fetch user's avatar code
				$query = $db->prepare($sel_sql->getUserAvatarByID);
				$query->setFetchMode(\PDO::FETCH_OBJ);
				$data = array('id' => $userID);
				$query->execute($data);
				$row = $query->fetch();
				$avatar = $row->avatar;
				
				if($_FILES['avatar']['error'] != 4){
					import('wddsocial.controller.WDDSocial\Uploader');
					\WDDSocial\Uploader::upload_user_avatar($_FILES['avatar'],"$avatar");
				}
			}
		}
	}
}