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
		if($_POST['terms'] != 'on' || $_POST['first-name'] == NULL || $_POST['last-name'] == NULL || $_POST['email'] == NULL || $_POST['full-sail-email'] == NULL || $_POST['password'] == NULL){
			echo render('wddsocial.view.WDDSocial\TemplateView', 
				array('section' => 'top', 'title' => 'Sign Up for WDD Social'));
			
			# open content section
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
			
			# display sign up form
			echo render('wddsocial.view.WDDSocial\FormView', array('type' => 'sign_up_intro'));
			
			if($_POST['terms'] != 'on'){
				$errorMessage = "You must agree to our <a href=\"{$root}terms\" title=\"WDD Social Terms of Service\">Terms of Service</a>, and complete all required fields.";
			}else{
				$errorMessage = "Please complete all required fields.";
			}
			# display sign up form
			echo render('wddsocial.view.WDDSocial\FormView', array('type' => 'sign_up', 'error' => "$errorMessage"));
			
			# end content section
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'end_content'));
			
			# display site footer
			echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'bottom'));
		}else{
			echo "<h1>POST DATA:</h1>";
			echo "<pre>";
			print_r($_POST);
			echo "</pre>";
			
			# Get db instance and query
			$db = instance(':db');
			$sel_sql = instance(':sel-sql');
			$admin_sql = instance(':admin-sql');
			
			# Get user type ID by title
			$query = $db->prepare($sel_sql->getUserTypeIDByTitle);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$data = array('title' => $_POST['user-type']);
			$query->execute($data);
			$row = $query->fetch();
			$typeID = $row->id;
			
			# Create vanityURL
			$vanityURL = $_POST['first-name'] . $_POST['last-name'];
			
			# Insert new user
			$query = $db->prepare($admin_sql->addUser);
			$data = array(	'typeID' => $typeID,
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
			echo "<h1>QUERY DATA:</h1>";
			echo "<pre>";
			print_r($data);
			echo "</pre>";
			
			//$query->execute($data);
			
			if($_FILES['avatar']['error'] != 4){
				import('wddsocial.controller.WDDSocial\Uploader');
				\WDDSocial\Uploader::upload_user_avatar($_FILES['avatar'],'test');
				echo "<pre>";
				print_r($_FILES);
				echo "</pre>";
			}
		}
	}
}