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
				$_POST['type'] = '';
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
		
		# open content section
		$content = render(':section', array('section' => 'begin_content'));
			
		if ($_POST['type'] == '') {
			$page_title = "Create Something.";
			
			$content .= render('wddsocial.view.form.WDDSocial\ExtraView', array('type' => 'create'));
			
			$content .= render('wddsocial.view.form.WDDSocial\ShareView', array('class' => 'small','error' => $response->message));
		}
		else {
			$page_title = "Create new {$_POST['type']}";
			
			# display basic form header
			$content .= render('wddsocial.view.form.pieces.WDDSocial\BasicElements', 
				array('section' => 'header', 'data' => $_POST, 
					'error' => $response->message, 'process' => 'creation'));
			
			# display content type-specific options
			if ($_POST['type'] == 'project' or $_POST['type'] == 'article' or 
			$_POST['type'] == 'event' or $_POST['type'] == 'job') {
				$typeCapitalized = ucfirst($_POST['type']);
				$content .= render("wddsocial.view.form.pieces.WDDSocial\\{$typeCapitalized}ExtraInputs",array('data' => $_POST));
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
				
				$content .= render('wddsocial.view.form.pieces.WDDSocial\TeamMemberInputs', array('header' => $teamTitle, 'type' => $_POST['type'], 'team' => $_POST['team'], 'roles' => $_POST['roles']));
			}
			
			# display image section
			$content .= render('wddsocial.view.form.pieces.WDDSocial\ImageInputs');
			
			# display video section
			$content .= render('wddsocial.view.form.pieces.WDDSocial\VideoInputs',array('videos' => $_POST['videos']));
			
			# display category section
			$content .= render('wddsocial.view.form.pieces.WDDSocial\CategoryInputs',array('categories' => $_POST['categories']));
			
			# display link section
			$content .= render('wddsocial.view.form.pieces.WDDSocial\LinkInputs',array('links' => $_POST['link-urls'], 'link-titles' => $_POST['link-titles']));
			
			#display course section
			if ($_POST['type'] != 'job') {
				$content .= render('wddsocial.view.form.pieces.WDDSocial\CourseInputs', array('header' => true, 'courses' => $_POST['courses']));
			}
			
			# display other options
			$content .= render('wddsocial.view.form.pieces.WDDSocial\OtherInputs', array('data' => $_POST));
			
			# display form footer
			$content .= render('wddsocial.view.form.pieces.WDDSocial\BasicElements', array('section' => 'footer'));
		}
		
		
		# end content section
		$content .= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', 
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
		
		if ($_POST['type'] == 'job' and $_FILES['company-avatar']['error'] != 4) {
			if (!Uploader::valid_image($_FILES['company-avatar'])) {
				return new FormResponse(false, "Please upload the company avatar in a supported image type (JPG, PNG, or GIF).");
			}
		}
		
		if (!Uploader::valid_images($_FILES['image-files'])) {
			return new FormResponse(false, "Please upload images in a supported image type (JPG, PNG, or GIF).");
		}
		
		$postTitle = strip_tags($_POST['title']);
		$postDescription = strip_tags($_POST['description']);
		$postContent = strip_tags($_POST['content'],'<link><header>');
		$postVanityURL = strtolower(preg_replace("#\W#", "", $_POST['vanityURL']));
		
		# Basic insert of content
		switch ($_POST['type']) {
			case 'project':
			
				if (isset($_POST['completed-date'])) {
					$completeDate = date_parse_from_format('F, Y',$_POST['completed-date']);
					if ($completeDate['error_count'] > 0) {
						return new FormResponse(false, implode('. ', $completedate['errors']));
					}
					$month = (strlen($completeDate['month']) == 1)?'0'.$completeDate['month']:$completeDate['month'];
					$postCompleteDate = $completeDate['year'] . '-' . $month . '-01';
				}
				else {
					$postCompleteDate = null;
				}
				
				$data = array(	'userID' => UserSession::userid(),
								'title' => $postTitle,
								'description' => $postDescription,
								'content' => ($postContent == '')?null:$postContent,
								'vanityURL' => ($postVanityURL == '')?null:$postVanityURL,
								'completeDate' => $postCompleteDate
				);
				$query = $db->prepare($admin_sql->addProject);
				break;
			case 'article':
				$data = array(	'userID' => UserSession::userid(),
								'privacyLevelID' => $_POST['privacy-level'],
								'title' => $postTitle,
								'description' => $postDescription,
								'content' => $postContent,
								'vanityURL' => ($postVanityURL == '')?null:$postVanityURL
				);
				$query = $db->prepare($admin_sql->addArticle);
				break;
			case 'event':
			
				if (isset($_POST['date'])) {
					$date = date_parse_from_format('F j, Y',$_POST['date']);
					if ($date['error_count'] > 0) {
						return new FormResponse(false, implode('. ', $date['errors']));
					}
					$month = (strlen($date['month']) == 1)?'0'.$date['month']:$date['month'];
					$day = (strlen($date['day']) == 1)?'0'.$date['day']:$date['day'];
					$startDate = $date['year'] . '-' . $month . '-' . $day;
				}
				
				if (isset($_POST['start-time'])) {
					$time = date_parse_from_format('g:i A',$_POST['start-time']);
					if ($time['error_count'] > 0) {
						return new FormResponse(false, implode('. ', $time['errors']));
					}
					$hour = (strlen($time['hour']) == 1)?'0'.$time['hour']:$time['hour'];
					$minute = (strlen($time['minute']) == 1)?'0'.$time['minute']:$time['minute'];
					$startTime = $hour . ':' . $minute . ':00';
				}
				
				$startDatetime = $startDate . ' ' . $startTime;
				$postLocation = strip_tags($_POST['location']);
				$postDuration = preg_replace('[\D]',"",$_POST['duraton']);
				$data = array(	'userID' => UserSession::userid(),
								'privacyLevelID' => $_POST['privacy-level'],
								'title' => $postTitle,
								'description' => $postDescription,
								'content' => ($postContent == '')?null:$postContent,
								'vanityURL' => ($postVanityURL == '')?null:$postVanityURL,
								'location' => $postLocation,
								'startDatetime' => $startDatetime,
								'duration' => ($postDuration == '' or $postDuration < 0)?1:$postDuration
				);
				$query = $db->prepare($admin_sql->addEvent);
				break;
			case 'job':
				$postCompany = strip_tags($_POST['company']);
				$postEmail = strip_tags($_POST['email']);
				$postLocation = strip_tags($_POST['location']);
				$postWebsite = strip_tags($_POST['website']);
				$postCompensation = strip_tags($_POST['compensation']);
				$data = array(	'userID' => UserSession::userid(),
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
			if (!Uploader::upload_employer_avatar($_FILES['company-avatar'],"{$result->avatar}")) {
				return new FormResponse(false, "Please upload the company avatar in a supported image type (JPG, PNG, or GIF).");
			}
		}
		
		if (!Uploader::upload_content_images($_FILES['image-files'], $_POST['image-titles'], $contentID, $_POST['title'], $_POST['type'])) {
			return new FormResponse(false, "Please upload images in a supported image type (JPG, PNG, or GIF).");
		}
		
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