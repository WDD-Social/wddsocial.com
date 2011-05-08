<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class CreatePage implements \Framework5\IExecutable {
	
	public function execute() {
		UserSession::protect();
		if(!isset($_POST['type'])){
			$request = \Framework5\Request::segment(1);
			if($request != '' AND ($request == 'project' or $request == 'article' or $request == 'event' or $request == 'job')){
				$_POST['type'] = $request;
			}else{
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
			
		else{
		# display site header
		echo render(':template', array('section' => 'top', 'title' => "Create new {$_POST['type']}"));
		
		# open content section
		echo render(':section', array('section' => 'begin_content'));
		
		# display basic form header
		echo render('wddsocial.view.form.create.WDDSocial\BasicElements', array('section' => 'header', 'data' => $_POST, 'error' => $response->message));
		
		# display content type-specific options
		if ($_POST['type'] == 'project' || $_POST['type'] == 'article' || $_POST['type'] == 'event' || $_POST['type'] == 'job') {
			$typeCapitalized = ucfirst($_POST['type']);
			echo render("wddsocial.view.form.create.WDDSocial\\{$typeCapitalized}ExtraInputs");
		}
		
		# display team member section for appropriate content types
		if ($_POST['type'] == 'project' || $_POST['type'] == 'article') {
			switch ($_POST['type']) {
				case 'project':
					$teamTitle = 'Team Members';
					break;
				case 'article':
					$teamTitle = 'Authors';
					break;
			}
			echo render('wddsocial.view.form.pieces.WDDSocial\TeamMemberInputs', array('header' => $teamTitle, 'type' => $_POST['type']));
		}
		
		# display image section
		echo render('wddsocial.view.form.pieces.WDDSocial\ImageInputs');
		
		# display video section
		echo render('wddsocial.view.form.pieces.WDDSocial\VideoInputs');
		
		# display category section
		echo render('wddsocial.view.form.pieces.WDDSocial\CategoryInputs');
		
		# display link section
		echo render('wddsocial.view.form.pieces.WDDSocial\LinkInputs');
		
		#display course section
		if ($_POST['type'] != 'job') {
			echo render('wddsocial.view.form.pieces.WDDSocial\CourseInputs');
		}
		
		# display other options
		echo render('wddsocial.view.form.pieces.WDDSocial\OtherInputs', $_POST);
		
		# display form footer
		echo render('wddsocial.view.form.create.WDDSocial\BasicElements', array('section' => 'footer'));
		
		# end content section
		echo render(':section', array('section' => 'end_content'));
		
		# display site footer
		echo render(':template', array('section' => 'bottom'));
		}
	}
	
	
	
	/**
	* Handle content creation
	*/
	
	private function _process_form() {
		import('wddsocial.model.WDDSocial\FormResponse');
		import('wddsocial.controller.WDDSocial\Uploader');
		
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
			$data = array('id' => $contentID);
			switch ($_POST['type']) {
				case 'project':
					$query = $db->prepare($admin_sql->generateProjectVanityURL);
					break;
				case 'article':
					$query = $db->prepare($admin_sql->generateArticleVanityURL);
					break;
				case 'event':
					$query = $db->prepare($admin_sql->generateEventVanityURL);
					break;
				case 'job':
					$query = $db->prepare($admin_sql->generateJobVanityURL);
					break;
			}
			$query->execute($data);
		}
		
		if($_POST['type'] == 'job' and $_FILES['company-avatar']['error'] != 4){
			$data = array('id' => $contentID);
			$query = $db->prepare($sel_sql->getJobAvatar);
			$query->execute($data);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$result = $query->fetch();
			Uploader::upload_employer_avatar($_FILES['company-avatar'],"{$result->avatar}");
		}
		
		for ($i = 0; $i < count($_FILES['image-files']['name']); $i++) {
			if ($_FILES['image-files']['error'][$i] != 4) {
				$image_num = $i + 1;
				$image_title = ($_POST['image-titles'][$i] == '')?"{$_POST['title']} | Image $image_num":$_POST['image-titles'][$i];
				
				$query = $db->prepare($admin_sql->addImage);
				$data = array(	'userID' => $_SESSION['user']->id,
								'title' => $image_title);
				$query->execute($data);
				
				$imageID = $db->lastInsertID();
				
				$query = $db->prepare($sel_sql->getImageFilename);
				$data = array('id' => $imageID);
				$query->execute($data);
				$query->setFetchMode(\PDO::FETCH_OBJ);
				$result = $query->fetch();
				
				switch ($_POST['type']) {
					case 'project':
						$data = array('projectID' => $contentID, 'imageID' => $imageID);
						$query = $db->prepare($admin_sql->addProjectImage);
						break;
					case 'article':
						$data = array('articleID' => $contentID, 'imageID' => $imageID);
						$query = $db->prepare($admin_sql->addArticleImage);
						break;
					case 'event':
						$data = array('eventID' => $contentID, 'imageID' => $imageID);
						$query = $db->prepare($admin_sql->addEventImage);
						break;
					case 'job':
						$data = array('jobID' => $contentID, 'imageID' => $imageID);
						$query = $db->prepare($admin_sql->addJobImage);
						break;
				}
				$query->execute($data);
				
				$newImage = array(	'tmp_name' => $_FILES['image-files']['tmp_name'][$i],
									'type' => $_FILES['image-files']['type'][$i]);
				Uploader::upload_image($newImage,"{$result->file}");
			}
		}
		
		# Get Vanity URL of new content to redirect there
		$data = array('id' => $contentID);
		switch ($_POST['type']) {
			case 'project':
				$query = $db->prepare($sel_sql->getProjectVanityURL);
				break;
			case 'article':
				$query = $db->prepare($sel_sql->getArticleVanityURL);
				break;
			case 'event':
				$query = $db->prepare($sel_sql->getEventVanityURL);
				break;
			case 'job':
				$query = $db->prepare($sel_sql->getJobVanityURL);
				break;
		}
		$query->execute($data);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$result = $query->fetch();
		
		return new FormResponse(true, "/{$_POST['type']}/{$result->vanityURL}");
	}
}