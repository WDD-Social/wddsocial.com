<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class AccountPage implements \Framework5\IExecutable {
	
	private $user;
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.UserPageLang');
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		$this->admin = instance(':admin-sql');
		$this->val = instance(':val-sql');
	}
	
	
	
	public function execute() {
		
		UserSession::protect();
		
		# get the request user
		$this->user = $this->get_user($_SESSION['user']->id);
		
		if (isset($_POST['submit'])) {
			$response = $this->process_form();
			
			if ($response->status) {
				redirect("/user/{$this->user->vanityURL}");
			}
			else {
				$errorMessage = $response->message;
			}
		}
		
		# display site header
		echo render(':template', array('section' => 'top', 'title' => 'My Account'));
			
		# open content section
		echo render(':section', array('section' => 'begin_content'));
		
		# display account form
		echo render('wddsocial.view.form.WDDSocial\AccountView', array('user' => $this->user, 'error' => $errorMessage));
			
		# end content section
		echo render(':section', array('section' => 'end_content'));
		
		# display site footer
		echo render(':template', array('section' => 'bottom'));
	}
	
	
	
	private function process_form(){
		import('wddsocial.model.WDDSocial\FormResponse');
		
		
		# Update basic data in user table
		
		$fields = array();
		$errors = array();
		
		if ($_POST['user-type'] !== $this->user->typeID)
			$fields['typeID'] = $_POST['user-type'];
		
		if ($_POST['first-name'] !== $this->user->firstName)
			$fields['firstName'] = $_POST['first-name'];
		
		if ($_POST['last-name'] !== $this->user->lastName)
			$fields['lastName'] = $_POST['last-name'];
		
		if ($_POST['email'] !== $this->user->email) {
			# Check if email is unique
			$query = $this->db->prepare($this->val->checkIfUserEmailExists);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$data = array('email' => $_POST['email']);
			$query->execute($data);
			$row = $query->fetch();
			if ($row->count > 0) {
				array_push($errors, 'email');
			}
			else {
				$fields['email'] = $_POST['email'];
			}
		}
		
		if ($_POST['full-sail-email'] !== $this->user->fullsailEmail) {
			# Check if Full Sail email is unique
			$query = $this->db->prepare($this->val->checkIfUserFullSailEmailExists);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$data = array('fullsailEmail' => $_POST['full-sail-email']);
			$query->execute($data);
			$row = $query->fetch();
			if ($row->count > 0) {
				array_push($errors, 'Full Sail email');
			}
			else {
				$fields['fullsailEmail'] = $_POST['full-sail-email'];
			}
		}
		
		if ($_POST['vanityURL'] !== $this->user->vanityURL) {
			# Check if vanityURL is unique
			$query = $this->db->prepare($this->val->checkIfUserVanityURLExists);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$data = array('vanityURL' => $_POST['vanityURL']);
			$query->execute($data);
			$row = $query->fetch();
			if ($row->count > 0) {
				array_push($errors, 'vanity URL');
			}
			else {
				$fields['vanityURL'] = $_POST['vanityURL'];
			}
		}
		
		if ($_POST['bio'] !== $this->user->bio)
			$fields['bio'] = $_POST['bio'];
				
		if ($_POST['hometown'] !== $this->user->hometown)
			$fields['hometown'] = $_POST['hometown'];
		
		if ($_POST['birthday'] !== $this->user->birthday)
			$fields['birthday'] = $_POST['birthday'];
		
		if ($_POST['website'] !== $this->user->contact['website'])
			$fields['website'] = $_POST['website'];
				
		if ($_POST['twitter'] !== $this->user->contact['twitter'])
			$fields['twitter'] = $_POST['twitter'];
		
		if ($_POST['facebook'] !== $this->user->contact['facebook'])
			$fields['facebook'] = $_POST['facebook'];
				
		if ($_POST['github'] !== $this->user->contact['github'])
			$fields['github'] = $_POST['github'];
		
		if ($_POST['dribbble'] !== $this->user->contact['dribbble'])
			$fields['dribbble'] = $_POST['dribbble'];
				
		if ($_POST['forrst'] !== $this->user->contact['forrst'])
			$fields['forrst'] = $_POST['forrst'];
		
		if (count($errors) > 0) {
			$errorMessage = "The ";
			if (count($errors) == 1) {
				$errorMessage .= "{$errors[0]} you provided is already in use.";
			}
			else{
				$errorMessage .=  NaturalLanguage::comma_list($errors);
			}
			$errorMessage .= " you provided are already in use. Your information must be unique, please try again.";
			return new FormResponse(false, $errorMessage);
		}
		
		$update = array();
		foreach ($fields as $fieldName => $fieldContent) {
			array_push($update,"$fieldName = '$fieldContent'");
		}
		$update = implode(', ',$update);
		if ($update !== '') {
			$query = $this->db->prepare($this->admin->updateUser . $update . " WHERE id = :id");
			$query->execute(array('id' => $this->user->id));
			$this->user = $this->get_user($this->user->id);
		}
		
		
		
		# Update userDetail data
		
		$fields = array();
		
		if (isset($_POST['start-date']) and $_POST['start-date'] !== $this->user->extra['startDateInput']) {
			$fields['startDate'] = ($_POST['start-date'] == '')?NULL:$_POST['start-date'];
		}
		
		if (isset($_POST['graduation-date']) and $_POST['graduation-date'] !== $this->user->extra['graduationDateInput']) {
			$fields['graduationDate'] = ($_POST['graduation-date'] == '')?NULL:$_POST['graduation-date'];
		}
		
		if (isset($_POST['degree-location']) and $_POST['degree-location'] !== $this->user->extra['location']) {
			$fields['location'] = ($_POST['degree-location'] == '')?NULL:$_POST['degree-location'];
		}
		
		if (isset($_POST['employer']) and $_POST['employer'] !== $this->user->extra['employerTitle']) {
			$fields['employerTitle'] = ($_POST['employer'] === '')?NULL:$_POST['employer'];
		}
		
		if (isset($_POST['employer-link']) and $_POST['employer-link'] !== $this->user->extra['employerLink']) {
			$fields['employerLink'] = ($_POST['employer-link'] === '')?NULL:$_POST['employer-link'];
		}
		
		$update = array();
		foreach ($fields as $fieldName => $fieldContent) {
			array_push($update,"$fieldName = '$fieldContent'");
		}
		$update = implode(', ',$update);
		if ($update !== '') {
			$query = $this->db->prepare($this->admin->updateUserDetail . $update . " WHERE userID = :id");
			$query->execute(array('id' => $this->user->id));
			$this->user = $this->get_user($this->user->id);
		}
			
		
		
		# Change Password	
		
		if (strlen($_POST['new-password']) > 0) {
			if (strlen($_POST['new-password']) < 6) {
				return new FormResponse(false, "Your new password was not long enough. Passwords must be at least 6 characters long, please try again.");
			}
			if (strlen($_POST['old-password']) <= 0) {
				return new FormResponse(false, "You need to enter your old password to change your password.");
			}
			else {
				$query = $this->db->prepare($this->val->checkUserPassword);
				$query->execute(array('id' => $this->user->id, 'password' => $_POST['old-password']));
				$query->setFetchMode(\PDO::FETCH_OBJ);
				$result = $query->fetch();
				if ($result->count > 0) {
					$query = $this->db->prepare($this->admin->changePassword);
					$query->execute(array('id' => $this->user->id, 'old' => $_POST['old-password'], 'new' => $_POST['new-password']));
				}
				else {
					return new FormResponse(false, "Your old password was incorrect, please try again.");
				}
			}
		}
		
		if($_FILES['avatar']['error'] != 4){
			import('wddsocial.controller.processes.WDDSocial\Uploader');
			Uploader::upload_user_avatar($_FILES['avatar'],"{$this->user->avatar}");
		}
		
		UserSession::refresh();
		return new FormResponse(true);
	}
	
	
	
	/**
	* Gets the user and data
	*/
	
	private function get_user($id){
		
		import('wddsocial.model.WDDSocial\UserVO');
		
		# query
		$data = array('id' => $id);
		$query = $this->db->prepare($this->sql->getUserByID);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
		$query->execute($data);
		return $query->fetch();
	}
}