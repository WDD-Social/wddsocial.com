<?php

namespace WDDSocial;

/*
* 
* Author: Anthony Colangelo (me@acolangelo.com)
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class CreatePage implements \Framework5\IExecutable {
	
	public function __construct() {
		//$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.PageLang');
	}
	
	public function execute() {
		
		# require user auth
		UserSession::protect();
		
		
		# if content type was not specified in post data
		if (!isset($_POST['type'])) {
			$request = \Framework5\Request::segment(1);
			if ($request != '' AND ($request == 'project' or $request == 'article' or 
			$request == 'event' or $request == 'job')){
				$_POST['type'] = $request;
			}
			else{
				$_POST['type'] = 'project';	
			}
		}
		
		# handle form submission
		if (isset($_POST['process']) and $_POST['process'] == 'creation'){
			$response = $this->_process_form();
			
			# redirect user on success
			if ($response->status) {
				# redirect user to new content page
				redirect("{$response->message}");
			}
		}
			
		
		
		$page_title = "Create new {$_POST['type']}";
		
		# open content section
		$content = render(':section', array('section' => 'begin_content'));
		
		# display basic form header
		$content .= render('wddsocial.view.form.create.WDDSocial\BasicElements', 
			array('section' => 'header', 'data' => $_POST, 
				'error' => $response->message, 'process' => 'creation'));
		
		# display content type-specific options
		if ($_POST['type'] == 'project' or $_POST['type'] == 'article' or 
		$_POST['type'] == 'event' or $_POST['type'] == 'job') {
			$typeCapitalized = ucfirst($_POST['type']);
			$content .= render("wddsocial.view.form.create.WDDSocial\\{$typeCapitalized}ExtraInputs");
		}
		
		# display team member section for appropriate content types
		if ($_POST['type'] == 'project' or $_POST['type'] == 'article') {
			switch ($_POST['type']) {
				case 'project':
					$teamTitle = 'Team Members';
					break;
				case 'article':
					$teamTitle = 'Authors';
					break;
			}
			
			$content .= render('wddsocial.view.form.pieces.WDDSocial\TeamMemberInputs', 
				array('header' => $teamTitle, 'type' => $_POST['type']));
		}
		
		# display image section
		$content .= render('wddsocial.view.form.pieces.WDDSocial\ImageInputs');
		
		# display video section
		$content .= render('wddsocial.view.form.pieces.WDDSocial\VideoInputs');
		
		# display category section
		$content .= render('wddsocial.view.form.pieces.WDDSocial\CategoryInputs');
		
		# display link section
		$content .= render('wddsocial.view.form.pieces.WDDSocial\LinkInputs');
		
		#display course section
		if ($_POST['type'] != 'job') {
			$content .= render('wddsocial.view.form.pieces.WDDSocial\CourseInputs', 
				array('header' => true));
		}
		
		# display other options
		$content .= render('wddsocial.view.form.pieces.WDDSocial\OtherInputs', 
			array('data' => $_POST));
		
		# display form footer
		$content .= render('wddsocial.view.form.create.WDDSocial\BasicElements', 
			array('section' => 'footer'));
		
		# end content section
		$content .= render(':section', array('section' => 'end_content'));
		
		
		# display page
		echo render('wddsocial.view.global.WDDSocial\SiteTemplate', 
			array('title' => $page_title, 'content' => $content));
	}
	
	
	
	/**
	* Handle content creation
	*/
	
	private function _process_form() {
		
		import('wddsocial.model.WDDSocial\FormResponse');
		import('wddsocial.controller.processes.WDDSocial\Uploader');
		import('wddsocial.controller.processes.WDDSocial\VanityURLProcessor');
		import('wddsocial.controller.processes.WDDSocial\TeamMemberProcessor');
		import('wddsocial.controller.processes.WDDSocial\CategoryProcessor');
		import('wddsocial.controller.processes.WDDSocial\CourseProcessor');
		import('wddsocial.controller.processes.WDDSocial\LinkProcessor');
		import('wddsocial.controller.processes.WDDSocial\VideoProcessor');
		
		# get database resources
		$db = instance(':db');
		$sel_sql = instance(':sel-sql');
		$admin_sql = instance(':admin-sql');
		$val_sql = instance(':val-sql');
		
		# check for required form values
		$required = array('title','description');
		
		switch ($_POST['type']) {
			case 'article':
				array_push($required,'content');
				break;
			case 'event':
				array_push($required,'location');
				array_push($required,'date');
				array_push($required,'start-time');
				break;
			case 'job':
				array_push($required,'company');
				array_push($required,'location');
				array_push($required,'email');
				break;
		}
		
		$incomplete = false;
		foreach ($required as $value) {
			if ($_POST[$value] == null) $incomplete = true;
		}
		
		if ($incomplete) {
			return new FormResponse(false, "Please complete all required fields.");
		}
		
		# Basic insert of content
		switch ($_POST['type']) {
			case 'project':
				$data = array(	'userID' => $_SESSION['user']->id,
								'title' => $_POST['title'],
								'description' => $_POST['description'],
								'content' => ($_POST['content'] == '')?null:$_POST['content'],
								'vanityURL' => ($_POST['vanityURL'] == '')?null:$_POST['vanityURL'],
								'completeDate' => ($_POST['completed-date'] == '')?null:$_POST['completed-date']
				);
				$query = $db->prepare($admin_sql->addProject);
				break;
			case 'article':
				$data = array(	'userID' => $_SESSION['user']->id,
								'privacyLevelID' => $_POST['privacy-level'],
								'title' => $_POST['title'],
								'description' => $_POST['description'],
								'content' => $_POST['content'],
								'vanityURL' => ($_POST['vanityURL'] == '')?null:$_POST['vanityURL']
				);
				$query = $db->prepare($admin_sql->addArticle);
				break;
			case 'event':
				$startDatetime = $_POST['date'] . ' ' . $_POST['start-time'];
				$data = array(	'userID' => $_SESSION['user']->id,
								'privacyLevelID' => $_POST['privacy-level'],
								'title' => $_POST['title'],
								'description' => $_POST['description'],
								'content' => ($_POST['content'] == '')?null:$_POST['content'],
								'vanityURL' => $_POST['vanityURL'],
								'location' => $_POST['location'],
								'startDatetime' => $startDatetime,
								'duration' => ($_POST['duration'] == '')?1:$_POST['duration'],
				);
				$query = $db->prepare($admin_sql->addEvent);
				break;
			case 'job':
				$data = array(	'userID' => $_SESSION['user']->id,
								'typeID' => $_POST['job-type'],
								'title' => $_POST['title'],
								'description' => $_POST['description'],
								'content' => ($_POST['content'] == '')?null:$_POST['content'],
								'vanityURL' => ($_POST['vanityURL'] == '')?null:$_POST['vanityURL'],
								'company' => $_POST['company'],
								'email' => $_POST['email'],
								'location' => $_POST['location'],
								'website' => ($_POST['website'] == '')?null:$_POST['website'],
								'compensation' => ($_POST['compensation'] == '')?null:$_POST['compensation']
				);
				$query = $db->prepare($admin_sql->addJob);
				break;
		}
		$query->execute($data);
		
		$contentID = $db->lastInsertID();
		
		# Generate Vanity URL if necessary
		if ($_POST['vanityURL'] == '') {
			VanityURLProcessor::generate($contentID, $_POST['type']);
		}
		
		if ($_POST['type'] == 'event') {
			$data = array('id' => $contentID);
			$query = $db->prepare($admin_sql->generateEventICSUID);
			$query->execute($data);
		}
		else if ($_POST['type'] == 'job') {
			$data = array('id' => $contentID);
			$query = $db->prepare($admin_sql->generateJobAvatar);
			$query->execute($data);
		}
		
		if ($_POST['type'] == 'project' or $_POST['type'] == 'article') {
			TeamMemberProcessor::add_team_members($_POST['team'], $contentID, $_POST['type'], $_POST['roles']);
		}
		
		CategoryProcessor::add_categories($_POST['categories'], $contentID, $_POST['type']);
		
		if ($_POST['type'] != 'job') {
			CourseProcessor::add_courses($_POST['courses'], $contentID, $_POST['type']);
		}
		
		LinkProcessor::add_links($_POST['link-urls'], $_POST['link-titles'], $contentID, $_POST['type']);
		
		if ($_POST['type'] == 'job' and $_FILES['company-avatar']['error'] != 4) {
			$data = array('id' => $contentID);
			$query = $db->prepare($sel_sql->getJobAvatar);
			$query->execute($data);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$result = $query->fetch();
			Uploader::upload_employer_avatar($_FILES['company-avatar'],"{$result->avatar}");
		}
		
		Uploader::upload_content_images($_FILES['image-files'], $_POST['image-titles'], $contentID, $_POST['title'], $_POST['type']);
		
		VideoProcessor::add_videos($_POST['videos'], $contentID, $_POST['type']);
		
		if ($_POST['type'] == 'event') {
			$data = array('id' => $contentID);
			$query = $db->prepare($sel_sql->getEventICSValues);
			$query->execute($data);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$event = $query->fetch();
			Uploader::create_ics_file($event);
		}
		
		# Get Vanity URL of new content to redirect there
		$contentVanityURL = VanityURLProcessor::get($contentID, $_POST['type']);
		
		return new FormResponse(true, "/{$_POST['type']}/{$contentVanityURL}");
	}
}