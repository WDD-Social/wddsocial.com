<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class AccountPage implements \Framework5\IExecutable {
	
	private $user;
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.user.AccountPageLang');
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		$this->admin = instance(':admin-sql');
		$this->val = instance(':val-sql');
	}
	
	
	
	public function execute() {
		
		# require user auth
		UserSession::protect();
		
		# get the request user
		$this->user = $this->get_user(UserSession::userid());
		
		
		if (isset($_POST['submit'])) {
			$response = $this->process_form();
			
			if ($response->status) {
				redirect("/user/{$this->user->vanityURL}");
			}
			else {
				$errorMessage = $response->message;
			}
		}
		
			
		# open content section
		$content = render(':section', array('section' => 'begin_content'));
		
		# display account form
		$content.= render('wddsocial.view.form.WDDSocial\AccountView', 
			array('user' => $this->user, 'error' => $errorMessage));
			
		# end content section
		$content.= render(':section', array('section' => 'end_content'));
		
		
		# display page
		echo render(':template', 
			array('title' => $this->lang->text('page-title'), 'content' => $content));
	}
	
	
	
	private function process_form(){
		
		import('wddsocial.model.WDDSocial\FormResponse');
		import('wddsocial.controller.processes.WDDSocial\CourseProcessor');
		import('wddsocial.controller.processes.WDDSocial\LikesDislikesProcessor');
		
		# Update basic data in user table
		
		$fields = array();
		$errors = array();
		$requires = array();
		
		$postFirstName = strip_tags($_POST['first-name']);
		$postLastName = strip_tags($_POST['last-name']);
		$postEmail = strip_tags($_POST['email']);
		$postFullSailEmail = strip_tags($_POST['full-sail-email']);
		$postVanityURL = strtolower(preg_replace("#\W#", "", $_POST['vanityURL']));
		
		if ($postFirstName == '') {
			array_push($requires, 'first name');
		}
		if ($postLastName == '') {
			array_push($requires, 'last name');
		}
		if ($postEmail == '') {
			array_push($requires, 'email address');
		}
		if ($postFullSailEmail == '') {
			array_push($requires, 'Full Sail email address');
		}
		if (count($requires) > 0) {
			$requireResponse = 'You must provide your ';
			if (count($requires) > 1) {
				$requireResponse .= NaturalLanguage::comma_list($requires);
			}
			else {
				$requireResponse .= $requires[0];
			}
			$requireResponse .= ".";
			return new FormResponse(false, $requireResponse);
		}
		
		if ($_POST['user-type'] != $this->user->typeID)
			$fields['typeID'] = $_POST['user-type'];
		
		if ($postFirstName != $this->user->firstName)
			$fields['firstName'] = $postFirstName;
		
		if ($postLastName != $this->user->lastName)
			$fields['lastName'] = $postLastName;
		
		if ($postEmail != $this->user->email) {
			# Check if email is unique
			$query = $this->db->prepare($this->val->checkIfUserEmailExists);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$data = array('email' => $postEmail);
			$query->execute($data);
			$row = $query->fetch();
			if ($row->count > 0) {
				array_push($errors, 'email');
			}
			else {
				$fields['email'] = $postEmail;
			}
		}
		
		if ($postFullSailEmail != $this->user->fullsailEmail) {
			# Check if Full Sail email is unique
			$query = $this->db->prepare($this->val->checkIfUserFullSailEmailExists);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$data = array('fullsailEmail' => $postFullSailEmail);
			$query->execute($data);
			$row = $query->fetch();
			if ($row->count > 0) {
				array_push($errors, 'Full Sail email');
			}
			else {
				$fields['fullsailEmail'] = $postFullSailEmail;
			}
		}
		
		if ($postVanityURL != $this->user->vanityURL) {
			if ($postVanityURL == '') {
				$vanityURL = '';
			
				# Check if vanity URL is unique, create a new one until a unique is found
				$query = $this->db->prepare($this->val->checkIfUserVanityURLExists);
				$query->setFetchMode(\PDO::FETCH_OBJ);
				
				$vanityURL = strtolower($postFirstName . $postLastName);
				for ($i = 0; $i < 100; $i++) {
					
					if ($i > 0) {
						$newVanityURL = $vanityURL . $i;
					}
					
					$data = array('vanityURL' => $newVanityURL);
					$query->execute($data);
					$row = $query->fetch();
					if ($row->count > 0) {	
						continue;
					}
					else{
						$fields['vanityURL'] = $newVanityURL;
					}
				}
			}
			else {
				# Check if vanityURL is unique
				$query = $this->db->prepare($this->val->checkIfUserVanityURLExists);
				$query->setFetchMode(\PDO::FETCH_OBJ);
				$data = array('vanityURL' => $postVanityURL);
				$query->execute($data);
				$row = $query->fetch();
				if ($row->count > 0) {
					array_push($errors, 'vanity URL');
				}
				else {
					$fields['vanityURL'] = $postVanityURL;
				}
			}
		}
		
		$postBio = strip_tags($_POST['bio']);
		if ($postBio != $this->user->bio)
			$fields['bio'] = $postBio;
				
		$postHometown = strip_tags($_POST['hometown']);
		if ($postHometown != $this->user->hometown)
			$fields['hometown'] = $postHometown;
		
		if (isset($_POST['birthday']) and $_POST['birthday'] != '') {
			$birthday = date_parse_from_format('F j, Y',$_POST['birthday']);
			if ($birthday['error_count'] > 0) {
				return new FormResponse(false, implode('. ', $birthday['errors']));
			}
			$month = (strlen($birthday['month']) == 1)?'0'.$birthday['month']:$birthday['month'];
			$day = (strlen($birthday['day']) == 1)?'0'.$birthday['day']:$birthday['day'];
			$birthdayDate = $birthday['year'] . '-' . $month . '-' . $day;
		}
		else {
			$birthdayDate = '';
		}
		
		if ($birthdayDate != $this->user->birthday)
			$fields['birthday'] = $birthdayDate;
		
		$postWebsite = strip_tags($_POST['website']);
		$postWebsite = StringCleaner::clean_link($postWebsite);
		if ($postWebsite != $this->user->contact['website'])
			$fields['website'] = $postWebsite;
				
		$postTwitter = strip_tags($_POST['twitter']);
		$postTwitter = StringCleaner::clean_twitter($postTwitter);
		if ($postTwitter != $this->user->contact['twitter'])
			$fields['twitter'] = $postTwitter;
		
		$postFacebook = strip_tags($_POST['facebook']);
		$postFacebook = StringCleaner::clean_facebook($postFacebook);
		if ($postFacebook != $this->user->contact['facebook'])
			$fields['facebook'] = $postFacebook;
				
		$postGithub = strip_tags($_POST['github']);
		$postGithub = StringCleaner::clean_github($postGithub);
		if ($postGithub != $this->user->contact['github'])
			$fields['github'] = $postGithub;
		
		$postDribbble = strip_tags($_POST['dribbble']);
		$postDribbble = StringCleaner::clean_dribbble($postDribbble);
		if ($postDribbble != $this->user->contact['dribbble'])
			$fields['dribbble'] = $postDribbble;
		
		$postForrst = strip_tags($_POST['forrst']);
		$postForrst = StringCleaner::clean_forrst($postForrst);
		if ($postForrst != $this->user->contact['forrst'])
			$fields['forrst'] = $postForrst;
		
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
		if ($update != '') {
			$query = $this->db->prepare($this->admin->updateUser . $update . " WHERE id = :id");
			$query->execute(array('id' => $this->user->id));
			$this->user = $this->get_user($this->user->id);
		}
		
		
		
		# Update userDetail data
		
		$fields = array();
		
		if (isset($_POST['start-date']) and $_POST['start-date'] != '') {
			$startDate = date_parse_from_format('F, Y',$_POST['start-date']);
			if ($startDate['error_count'] > 0) {
				return new FormResponse(false, implode('. ', $startDate['errors']));
			}
			$month = (strlen($startDate['month']) == 1)?'0'.$startDate['month']:$startDate['month'];
			$startDate = $startDate['year'] . '-' . $month . '-01';
		}
		else {
			$startDate = '';
		}
		
		if ($startDate != $this->user->extra['startDate']) {
			$fields['startDate'] = ($startDate == '')?NULL:$startDate;
		}
		
		if (isset($_POST['graduation-date']) and $_POST['graduation-date'] != '') {
			$graduationDate = date_parse_from_format('F, Y',$_POST['graduation-date']);
			if ($graduationDate['error_count'] > 0) {
				return new FormResponse(false, implode('. ', $graduationDate['errors']));
			}
			$month = (strlen($graduationDate['month']) == 1)?'0'.$graduationDate['month']:$graduationDate['month'];
			$graduationDate = $graduationDate['year'] . '-' . $month . '-01';
		}
		else {
			$graduationDate = '';
		}
		
		if ($graduationDate != $this->user->extra['graduationDate']) {
			$fields['graduationDate'] = ($graduationDate == '')?NULL:$graduationDate;
		}
		
		if (isset($_POST['degree-location']) and $_POST['degree-location'] != $this->user->extra['location']) {
			$fields['location'] = ($_POST['degree-location'] == '')?NULL:$_POST['degree-location'];
		}
		
		$postEmployer = strip_tags($_POST['employer']);
		if (isset($postEmployer) and $postEmployer != $this->user->extra['employerTitle']) {
			$fields['employerTitle'] = ($postEmployer === '')?NULL:$postEmployer;
		}
		
		$postEmployerLink = strip_tags($_POST['employer-link']);
		if (isset($postEmployerLink) and $postEmployerLink != $this->user->extra['employerLink']) {
			$fields['employerLink'] = ($postEmployerLink === '')?NULL:$postEmployerLink;
		}
		
		$update = array();
		foreach ($fields as $fieldName => $fieldContent) {
			array_push($update,"$fieldName = '$fieldContent'");
		}
		$update = implode(', ',$update);
		if ($update != '') {
			$query = $this->db->prepare($this->admin->updateUserDetail . $update . " WHERE userID = :id");
			$query->execute(array('id' => $this->user->id));
			$this->user = $this->get_user($this->user->id);
		}
		
		
		
		# Update teacher courses, if user is a teacher
		
		if ($_POST['user-type'] == 2) {
			$currentCourses = array();
			$newCourses = array();
			foreach ($this->user->extra['courses'] as $currentCourse) {
				array_push($currentCourses, $currentCourse->id);
			}
			foreach ($_POST['courses'] as $newCourse) {
				if ($newCourse != '')
					array_push($newCourses, $newCourse);
			}
			CourseProcessor::update_courses($currentCourses, $newCourses, 'user', $this->user->id);
		}
		
		
		
		# Update likes/dislikes
		
		$newLikes = array();
		foreach ($_POST['likes'] as $newLike) {
			if ($newLike != '')
				array_push($newLikes, $newLike);
		}
		
		$newDislikes = array();
		foreach ($_POST['dislikes'] as $newDislike) {
			if ($newDislike != '')
				array_push($newDislikes, $newDislike);
		}
		$currentLikes = $this->user->extra['likes'];
		$currentDislikes = $this->user->extra['dislikes'];
		
		LikesDislikesProcessor::update_likes($this->user->id, $currentLikes, $newLikes);
		LikesDislikesProcessor::update_dislikes($this->user->id, $currentDislikes, $newDislikes);
		
		
		
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