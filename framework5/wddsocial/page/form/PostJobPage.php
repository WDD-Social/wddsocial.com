<?php

namespace WDDSocial;

/*
* 
* Author: Anthony Colangelo (me@acolangelo.com)
*/

class PostJobPage implements \Framework5\IExecutable {
	
	public function __construct() {
		
	}
	
	public function execute() {
		if (UserSession::is_authorized()) redirect('create/job');
		
		# handle form submission
		if (isset($_POST['process']) and $_POST['process'] == 'creation'){
			$response = $this->_process_form();
		}
		
		# open content section
		$content = render(':section', array('section' => 'begin_content'));
		
		if ($response->status) {
			$content .= "<h1>Job has been posted.</h1>";
		}
		else {
			$content .= render('wddsocial.view.form.WDDSocial\ExtraView', array('type' => 'postajob'));
			
			# display basic form header
			$content .= render('wddsocial.view.form.pieces.WDDSocial\BasicElements', 
				array('section' => 'header',
				'data' => $_POST, 
				'error' => $response->message,
				'process' => 'creation')
			);
			
			$content .= render('wddsocial.view.form.pieces.WDDSocial\JobExtraInputs');
			
			# display image section
			$content .= render('wddsocial.view.form.pieces.WDDSocial\ImageInputs');
			
			# display video section
			$content .= render('wddsocial.view.form.pieces.WDDSocial\VideoInputs');
			
			# display category section
			$content .= render('wddsocial.view.form.pieces.WDDSocial\CategoryInputs');
			
			# display link section
			$content .= render('wddsocial.view.form.pieces.WDDSocial\LinkInputs');
			
			# display other options
			$content .= render('wddsocial.view.form.pieces.WDDSocial\OtherInputs', array('data' => $_POST));
			
			# display form footer
			$content .= render('wddsocial.view.form.pieces.WDDSocial\BasicElements', array('section' => 'footer'));
		}
				
		# end content section
		$content .= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', array('title' => 'Post a Job', 'content' => $content));
	}
	
	
	
	/**
	* Handle content creation
	*/
	
	private function _process_form() {
		
		import('wddsocial.model.WDDSocial\FormResponse');
		import('wddsocial.controller.processes.WDDSocial\Uploader');
		import('wddsocial.controller.processes.WDDSocial\VanityURLProcessor');
		import('wddsocial.controller.processes.WDDSocial\CategoryProcessor');
		import('wddsocial.controller.processes.WDDSocial\LinkProcessor');
		import('wddsocial.controller.processes.WDDSocial\VideoProcessor');
		
		# get database resources
		$db = instance(':db');
		$sel_sql = instance(':sel-sql');
		$admin_sql = instance(':admin-sql');
		$val_sql = instance(':val-sql');
		
		# check for required form values
		$required = array('title','description','company','location','email');
		
		$incomplete = false;
		foreach ($required as $value) {
			if ($_POST[$value] == null) $incomplete = true;
		}
		
		if ($incomplete) {
			return new FormResponse(false, "Please complete all required fields.");
		}
		
		$postTitle = strip_tags($_POST['title']);
		$postDescription = strip_tags($_POST['description']);
		$postContent = strip_tags($_POST['content'],'<link><header>');
		$postVanityURL = strtolower(preg_replace("#\W#", "", $_POST['vanityURL']));
		
		# Basic insert of content
		$postCompany = strip_tags($_POST['company']);
		$postEmail = strip_tags($_POST['email']);
		$postLocation = strip_tags($_POST['location']);
		$postWebsite = strip_tags($_POST['website']);
		$postCompensation = strip_tags($_POST['compensation']);
		$data = array(	'userID' => NULL,
						'typeID' => $_POST['job-type'],
						'title' => $postTitle,
						'description' => $postDescription,
						'content' => ($postContent == '')?null:$postContent,
						'vanityURL' => ($postVanityURL == '')?null:$postVanityURL,
						'company' => $postCompany,
						'email' => $postEmail,
						'location' => $postLocation,
						'website' => ($postWebsite == '')?null:$postWebsite,
						'compensation' => ($postCompensation == '')?null:$postCompensation
		);
		$query = $db->prepare($admin_sql->addJob);
		$query->execute($data);
		
		$contentID = $db->lastInsertID();
		
		$query = $db->prepare($admin_sql->generateJobSecurityCode);
		$query->execute(array('id' => $contentID));
		
		# Generate Vanity URL if necessary
		if ($_POST['vanityURL'] == '') {
			VanityURLProcessor::generate($contentID, 'job');
		}
		
		$data = array('id' => $contentID);
		$query = $db->prepare($admin_sql->generateJobAvatar);
		$query->execute($data);
		
		CategoryProcessor::add_categories($_POST['categories'], $contentID, 'job');
		
		LinkProcessor::add_links($_POST['link-urls'], $_POST['link-titles'], $contentID, 'job');
		
		if ($_FILES['company-avatar']['error'] != 4) {
			$data = array('id' => $contentID);
			$query = $db->prepare($sel_sql->getJobAvatar);
			$query->execute($data);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$result = $query->fetch();
			Uploader::upload_employer_avatar($_FILES['company-avatar'],"{$result->avatar}");
		}
		
		Uploader::upload_content_images($_FILES['image-files'], $_POST['image-titles'], $contentID, $_POST['title'], 'job');
		
		VideoProcessor::add_videos($_POST['videos'], $contentID, 'job');
		
		return new FormResponse(true);
	}
}