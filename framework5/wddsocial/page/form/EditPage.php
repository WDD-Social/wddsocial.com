<?php

namespace WDDSocial;

/*
* 
* Author: Anthony Colangelo (me@acolangelo.com)
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class EditPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->db = instance(':db');
		$this->sel = instance(':sel-sql');
		$this->val = instance(':val-sql');
		$this->admin = instance(':admin-sql');
	}
	
	
	
	public function execute() {
		
		# require user auth
		if (\Framework5\Request::segment(1) != 'job') {
			UserSession::protect();
		}
		
		# handle form submission
		if (isset($_POST['submit'])){
			$response = $this->_process_form();
			
			# redirect user on success
			if ($response->status) {
				# redirect user to new content page
				redirect("{$response->message}");
			}
			
			$content = $_POST;
		}
			
		$types = array('project', 'article', 'event', 'job', 'comment');
		$type = \Framework5\Request::segment(1);
		$vanityURL = \Framework5\Request::segment(2);
		
		# if type is not valid, redirect
		if (!in_array($type, $types) or !isset($vanityURL)) redirect('/');
		
		# set query based on content type
		switch ($type) {
			case 'project':
				$query = $this->db->prepare($this->sel->getProjectByVanityURL);
				break;
			case 'article':
				$query = $this->db->prepare($this->sel->getArticleByVanityURL);
				break;
			case 'event':
				$query = $this->db->prepare($this->sel->getEventByVanityURL);
				break;
			case 'job':
				$query = $this->db->prepare($this->sel->getJobByVanityURL);
				break;
			case 'comment':
				$query = $this->db->prepare($this->sel->getCommentByID);
				break;
			default:
				redirect('/');
				break;
		}
		
		if ($type == 'job') {
			import('wddsocial.model.WDDSocial\JobVO');
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\JobVO');
		}
		else if ($type == 'comment') {
			$query->setFetchMode(\PDO::FETCH_OBJ);
		}
		else {
			import('wddsocial.model.WDDSocial\ContentVO');
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ContentVO');
		}
		
		if ($type == 'comment') {
			$query->execute(array('id' => $vanityURL));
		}
		else {
			$query->execute(array('vanityURL' => $vanityURL));
		}
		
		# if content exists
		if ($query->rowCount() > 0) {
			$content = $query->fetch();
			switch ($type) {
				case 'job':
					$securityCode = \Framework5\Request::segment(3);
					if (UserSession::is_authorized()) {
						if (!UserValidator::is_owner($content->id,$type)) redirect('/');
					}
					else {
						if ($securityCode != $content->securityCode) redirect('/');
					}
					break;
				default:
					if (!UserValidator::is_owner($content->id,$type)) {
						redirect('/');
					}
					break;
			}
		}
		
		# invalid content
		else {
			redirect('/');
		}
		
		
		# page title
		$typeTitle = ucfirst($content->type);
		$contentTitle = ($type == 'comment')?"Comment":$content->title;
		$page_title = "Edit {$typeTitle} | {$contentTitle}";
		
		# open content section
		$html = render(':section', array('section' => 'begin_content'));
		
		if ($type == 'comment') {
			$html.= render('wddsocial.view.form.WDDSocial\CommentEditView', 
				array('data' => $content, 'error' => $response->message));
		}
		
		else {
		
			# display basic form header
			$html.= render('wddsocial.view.form.pieces.WDDSocial\BasicElements', 
				array('section' => 'header', 'data' => $content, 
					'error' => $response->message, 'process' => 'edit'));
			
			# display content type-specific options
			if ($content->type == 'project' or $content->type == 'article' or 
			$content->type == 'event' or $content->type == 'job') {
				$typeCapitalized = ucfirst($content->type);
				$html.= render("wddsocial.view.form.pieces.WDDSocial\\{$typeCapitalized}ExtraInputs", 
					array('data' => $content));
			}
			
			# Save button
			$html.= render('wddsocial.view.form.pieces.WDDSocial\BasicElements', 
				array('section' => 'save'));
			
			# display team member section for appropriate content types
			if ($content->type == 'project' or $content->type == 'article') {
				switch ($content->type) {
					case 'project':
						$teamTitle = 'Team Members';
						break;
					
					case 'article':
						$teamTitle = 'Authors';
						break;
				}
				
				$html.= render('wddsocial.view.form.pieces.WDDSocial\TeamMemberInputs', 
					array('header' => $teamTitle, 'type' => $content->type, 'team' => $content->team));
				
				# Save button
				$html.= render('wddsocial.view.form.pieces.WDDSocial\BasicElements', 
					array('section' => 'save'));
			}
			
			# display image section
			$html.= render('wddsocial.view.form.pieces.WDDSocial\ImageInputs', 
				array('images' => $content->images));
			
			# Save button
			$html.= render('wddsocial.view.form.pieces.WDDSocial\BasicElements', 
				array('section' => 'save'));
			
			# display video section
			$html.= render('wddsocial.view.form.pieces.WDDSocial\VideoInputs', 
				array('videos' => $content->videos));
			
			# display category section
			$html.= render('wddsocial.view.form.pieces.WDDSocial\CategoryInputs', 
				array('categories' => $content->categories));
			
			# Save button
			$html.= render('wddsocial.view.form.pieces.WDDSocial\BasicElements', 
				array('section' => 'save'));
			
			# display link section
			$html.= render('wddsocial.view.form.pieces.WDDSocial\LinkInputs', 
				array('links' => $content->links));
			
			# Save button
			$html.= render('wddsocial.view.form.pieces.WDDSocial\BasicElements', 
				array('section' => 'save'));
			
			#display course section
			if ($content->type != 'job') {
				$html.= render('wddsocial.view.form.pieces.WDDSocial\CourseInputs', 
					array('courses' => $content->courses, 'header' => true));
			}
			
			# display other options
			$html.= render('wddsocial.view.form.pieces.WDDSocial\OtherInputs', 
				array('data' => $content));
			
			# display form footer
			$html.= render('wddsocial.view.form.pieces.WDDSocial\BasicElements', 
				array('section' => 'footer'));
		}
		
		# end content section
		$html.= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', 
			array('title' => $page_title, 'content' => $html));
	}
	
	
	
	/**
	* Handle content editing
	*/
	
	private function _process_form() {
		
		# import dependencies
		import('wddsocial.model.WDDSocial\ContentVO');
		import('wddsocial.model.WDDSocial\FormResponse');
		import('wddsocial.controller.processes.WDDSocial\CategoryProcessor');
		import('wddsocial.controller.processes.WDDSocial\CourseProcessor');
		import('wddsocial.controller.processes.WDDSocial\TeamMemberProcessor');
		import('wddsocial.controller.processes.WDDSocial\LinkProcessor');
		import('wddsocial.controller.processes.WDDSocial\VideoProcessor');
		import('wddsocial.controller.processes.WDDSocial\VanityURLProcessor');
		import('wddsocial.controller.processes.WDDSocial\Uploader');
		import('wddsocial.controller.processes.WDDSocial\Deleter');
		
		
		# Get basic content data
		switch ($_POST['type']) {
			case 'project':
				$query = $this->db->prepare($this->sel->getProjectByID);
				break;
			case 'article':
				$query = $this->db->prepare($this->sel->getArticleByID);
				break;
			case 'event':
				$query = $this->db->prepare($this->sel->getEventByID);
				break;
			case 'job':
				$query = $this->db->prepare($this->sel->getJobByID);
				break;
			case 'comment':
				$query = $this->db->prepare($this->sel->getCommentByID);
				break;
		}
		$query->execute(array('id' => $_POST['contentID']));
		
		if ($_POST['type'] == 'comment') {
			$query->setFetchMode(\PDO::FETCH_OBJ);
		}
		else {
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ContentVO');
		}
		
		$content = $query->fetch();
		
		if ($_POST['type'] == 'comment') {
			if (isset($_POST['content']) and $_POST['content'] != '') {
				$postContent = strip_tags($_POST['content']);
				$query = $this->db->prepare($this->admin->updateComment);
				$query->execute(array('id' => $_POST['contentID'], 'content' => $postContent));
			}
			
			# redirect to page
			$redirectLocation = "{$_POST['redirect']}";
		}
		else {
			# Get basic fields for update
			$fields = array();
		
			if ($content->type == 'job' and $_FILES['company-avatar']['error'] != 4) {
				if (!Uploader::valid_image($_FILES['company-avatar'])) {
					return new FormResponse(false, "Please upload the company avatar in a supported image type (JPG, PNG, or GIF).");
				}
			}
			
			$postTitle = strip_tags($_POST['title']);
			$postDescription = strip_tags($_POST['description']);
			$postContent = strip_tags($_POST['content'],'<link><header>');
			$postVanityURL = strtolower(preg_replace("#\W#", "", $_POST['vanityURL']));
			
			if ($postTitle != $content->title)
				$fields['title'] = $postTitle;
			
			if ($postDescription != $content->description)
				$fields['description'] = $postDescription;
			
			if ($postContent != $content->content)
				$fields['content'] = $postContent;
			
			if ($postVanityURL != $content->vanityURL and $postVanityURL != '') {
				$fields['vanityURL'] = $postVanityURL;
				$vanityURLChanged = true;
			}
			else if ($postVanityURL == '')
				VanityURLProcessor::generate($content->id, $content->type);
			
			switch ($content->type) {
				case 'project':
			
					if (isset($_POST['completed-date']) and $_POST['completed-date'] != '') {
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
					
					if ($postCompleteDate != $content->completeDate)
						$fields['completeDate'] = $postCompleteDate;
					break;
				case 'event':
				
					if (isset($_POST['date']) and $_POST['date'] != '') {
						$date = date_parse_from_format('F j, Y',$_POST['date']);
						if ($date['error_count'] > 0) {
							return new FormResponse(false, implode('. ', $date['errors']));
						}
						$month = (strlen($date['month']) == 1)?'0'.$date['month']:$date['month'];
						$day = (strlen($date['day']) == 1)?'0'.$date['day']:$date['day'];
						$startDate = $date['year'] . '-' . $month . '-' . $day;
					}
					
					if (isset($_POST['start-time']) and $_POST['start-time'] != '') {
						$time = date_parse_from_format('g:i A',$_POST['start-time']);
						if ($time['error_count'] > 0) {
							return new FormResponse(false, implode('. ', $time['errors']));
						}
						$hour = (strlen($time['hour']) == 1)?'0'.$time['hour']:$time['hour'];
						$minute = (strlen($time['minute']) == 1)?'0'.$time['minute']:$time['minute'];
						$startTime = $hour . ':' . $minute . ':00';
					}
					
					$baseDatetime = $startDate . ' ' . $startTime;
					$postLocation = strip_tags($_POST['location']);
					$postDuration = (is_numeric($_POST['duration']))?$_POST['duration']:2;
					
					if ($postLocation != $content->location)
						$fields['location'] = $postLocation;
					if ($postDuration != $content->duration)
						$content->duration = $postDuration;
					if ($content->duration < 1)
						$content->duration = 1;
					$fields['startDatetime'] = $baseDatetime;
					$fields['endDatetime'] = "DATE_ADD('{$baseDatetime}', INTERVAL {$content->duration} HOUR)";
					break;
				case 'job':
					$postCompany = strip_tags($_POST['company']);
					$postEmail = strip_tags($_POST['email']);
					$postLocation = strip_tags($_POST['location']);
					$postWebsite = strip_tags($_POST['website']);
					$postCompensation = strip_tags($_POST['compensation']);
					
					if ($postCompany != $content->company)
						$fields['company'] = $postCompany;
					if ($postEmail != $content->email)
						$fields['email'] = $postEmail;
					if ($postLocation != $content->location)
						$fields['location'] = $postLocation;
					if ($postWebsite != $content->website)
						$fields['website'] = $postWebsite;
					if ($postCompensation != $content->compensation)
						$fields['compensation'] = $postCompensation;
					break;
			}
			
			
			
			# Update basic fields
			if (count($fields) > 0) {
				$update = array();
				foreach ($fields as $fieldName => $fieldContent) {
					if ($fieldName == 'endDatetime') {
						array_push($update,"endDatetime = $fieldContent");
					}
					else {
						$cleanString = addslashes($fieldContent);
						array_push($update,"$fieldName = '$cleanString'");
					}
				}
				$update = implode(', ',$update);
				if (is_array($update)) {
					$update = '';
				}
	
				if ($update != '') {
					$update .= " WHERE id = :id";
					$data = array('id' => $content->id);
					switch ($content->type) {
						case 'project':
							$query = $this->db->prepare($this->admin->updateProject . $update);
							break;
						case 'article':
							$query = $this->db->prepare($this->admin->updateArticle . $update);
							break;
						case 'event':
							$query = $this->db->prepare($this->admin->updateEvent . $update);
							break;
						case 'job':
							$query = $this->db->prepare($this->admin->updateJob . $update);
							break;
					}
					$query->execute($data);
					if ($content->type == 'event') {
						$data = array('id' => $content->id);
						$query = $this->db->prepare($this->sel->getEventICSValues);
						$query->execute($data);
						$query->setFetchMode(\PDO::FETCH_OBJ);
						$event = $query->fetch();
						Uploader::create_ics_file($event);
					}
				}
			}
			
			
			
			# Update team, if there is a team
			if (isset($_POST['team'])) {
				$currentMembers = array();
				$newMembers = array();
				$currentRoles = array();
				$newRoles = array();
				foreach ($content->team as $currentMember) {
					array_push($currentMembers, "{$currentMember->firstName} {$currentMember->lastName}");
					if ($content->type == 'project')
						array_push($currentRoles, $currentMember->role);
				}
				foreach ($_POST['team'] as $newMember) {
					if ($newMember != '')
						array_push($newMembers, $newMember);
				}
				if ($content->type == 'project')
					$newRoles = $_POST['roles'];
				TeamMemberProcessor::update_team_members($currentMembers, $newMembers, $content->id, $content->type, $currentRoles, $newRoles);
			}
			
			
			
			# Add/Edit/Delete images
			if (isset($_POST['existing-image-status'])) {
				foreach ($_POST['existing-image-status'] as $imageFile) {
					Deleter::delete_content_image($imageFile);
				}
			}
			if (isset($_POST['existing-image-files'])) {
				foreach ($_POST['existing-image-files'] as $index => $file) {
					$query = $this->db->prepare($this->val->checkIfImageExists);
					$query->execute(array('file' => $file));
					if ($query->rowCount() > 0) {
						$query->setFetchMode(\PDO::FETCH_OBJ);
						$result = $query->fetch();
						$imageID = $result->id;
						$imageTitle = ($_POST['existing-image-titles'][$index] != '')?$_POST['existing-image-titles'][$index]:$_POST['title'] . " | Image";
						$query = $this->db->prepare($this->admin->updateImage);
						$query->execute(array('id' => $imageID, 'title' => $imageTitle));
					}
				}
			}
			if (!Uploader::upload_content_images($_FILES['image-files'], $_POST['image-titles'], $content->id, $_POST['title'], $content->type)) {
				return new FormResponse(false, "Please upload images in a supported image type (JPG, PNG, or GIF).");
			}
			
			
			
			# Add/Edit/Delete videos
			$postvideos = array();
			foreach ($_POST['videos'] as $pvideo) {
				if ($pvideo != '')
					array_push($postvideos, htmlspecialchars("$pvideo"));
			}
			$contentvideos = array();
			foreach ($content->videos as $cvideo) {
				array_push($contentvideos, htmlspecialchars("{$cvideo->embedCode}"));
			}
			VideoProcessor::update_videos($contentvideos, $postvideos, $content->id, $content->type);
			
			
			
			# Add/Edit/Delete categories
			$currentCategories = array();
			$newCategories = array();
			foreach ($content->categories as $currentCategory) {
				array_push($currentCategories, $currentCategory->title);
			}
			foreach ($_POST['categories'] as $newCategory) {
				if ($newCategory != '')
					array_push($newCategories, $newCategory);
			}
			CategoryProcessor::update_categories($currentCategories, $newCategories, $content->type, $content->id);
			
			
			
			# Add/Edit/Delete links
			$currentLinks = array();
			$newLinks = array();
			$currentTitles = array();
			$newTitles = array();
			foreach ($content->links as $currentLink) {
				array_push($currentLinks, $currentLink->link);
				array_push($currentTitles, $currentLink->title);
			}
			foreach ($_POST['link-urls'] as $linkURL) {
				array_push($newLinks, $linkURL);
			}
			foreach ($_POST['link-titles'] as $linkTitle) {
				array_push($newTitles, $linkTitle);
			}
			LinkProcessor::update_links($currentLinks, $newLinks,  $currentTitles, $newTitles, $content->id, $content->type);
			
			
			
			if ($content->type != 'job') {
				# Add/Edit/Delete Courses
				$currentCourses = array();
				$newCourses = array();
				foreach ($content->courses as $currentCourse) {
					array_push($currentCourses, $currentCourse->id);
				}
				foreach ($_POST['courses'] as $newCourse) {
					if ($newCourse != '')
						array_push($newCourses, $newCourse);
				}
				CourseProcessor::update_courses($currentCourses, $newCourses, $content->type, $content->id);
			}
						
			
			
			# Redirect to content page
			$contentVanityURL = VanityURLProcessor::get($content->id, $content->type);
			
			if (UserSession::is_authorized()) {
				$redirectLocation = "/{$content->type}/{$contentVanityURL}";
			}
			else if ($content->type == 'job') {
				$redirectLocation = "/confirm/editjob";
				
				if ($vanityURLChanged) {
					# get edit code for email
					$query = $this->db->prepare($this->sel->getJobEditInfo);
					$query->execute(array('id' => $content->id));
					$query->setFetchMode(\PDO::FETCH_OBJ);
					$result = $query->fetch();
					
					# send edit job post email
					import('wddsocial.controller.WDDSocial\Mailer');
					
					$mailer = new Mailer();
					$mailer->add_recipient($postCompany, $postEmail);
					$mailer->subject = "WDD Social | Job Post \"{$postTitle}\"";
					$mailer->message = render("wddsocial.view.email.WDDSocial\EditJobEmail",
						array('vanityURL' => $result->vanityURL, 'securityCode' => $result->securityCode));
					$mailer->send();
				}
			}
		}
		
		return new FormResponse(true, "{$redirectLocation}");
	}
}