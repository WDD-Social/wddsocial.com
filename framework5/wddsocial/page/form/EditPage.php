<?php

namespace WDDSocial;

/*
* 
* Author: Anthony Colangelo (me@acolangelo.com)
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class EditPage implements \Framework5\IExecutable {
	
	public function execute() {
		UserSession::protect();
		
		$this->db = instance(':db');
		$this->sel = instance(':sel-sql');
		$this->val = instance(':val-sql');
		$this->admin = instance(':admin-sql');
		
		# handle form submission
		if (isset($_POST['submit'])){
			$response = $this->_process_form();
			
			# redirect user on success
			if ($response->status) {
				# redirect user to new content page
				redirect("{$response->message}");
			}
		}
		
		
		
		
		else {
		
		
		
		
		$types = array('project','article','event','job');
		$type = \Framework5\Request::segment(1);
		$vanityURL = \Framework5\Request::segment(2);
		if (!in_array($type, $types) or !isset($vanityURL))
			redirect('/');
		
		import('wddsocial.model.WDDSocial\ContentVO');
		
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
			default:
				redirect('/');
				break;
		}
		$query->execute(array('vanityURL' => $vanityURL));
		
		if ($query->rowCount() > 0) {
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ContentVO');
			$content = $query->fetch();
			switch ($type) {
				case 'project':
					if (!UserValidator::is_project_owner($content->id)) {
						redirect('/');
					}
					break;
				case 'article':
					if (!UserValidator::is_article_owner($content->id)) {
						redirect('/');
					}
					break;
				case 'event':
					if (!UserValidator::is_event_owner($content->id)) {
						redirect('/');
					}
					break;
				case 'job':
					if (!UserValidator::is_job_owner($content->id)) {
						redirect('/');
					}
					break;
			}
		}
		else {
			redirect('/');
		}
		
		$typeTitle = ucfirst($content->type);
		
		# display site header
		echo render(':template', array('section' => 'top', 'title' => "Edit {$typeTitle} | {$content->title}"));
		
		# open content section
		echo render(':section', array('section' => 'begin_content'));
		
		# display basic form header
		echo render('wddsocial.view.form.create.WDDSocial\BasicElements', array('section' => 'header', 'data' => $content, 'error' => $response->message, 'process' => 'edit'));
		
		# display content type-specific options
		if ($content->type == 'project' or $content->type == 'article' or $content->type == 'event' or $content->type == 'job') {
			$typeCapitalized = ucfirst($content->type);
			echo render("wddsocial.view.form.create.WDDSocial\\{$typeCapitalized}ExtraInputs", array('data' => $content));
		}
		
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
			echo render('wddsocial.view.form.pieces.WDDSocial\TeamMemberInputs', array('header' => $teamTitle, 'type' => $content->type, 'team' => $content->team));
		}
		
		/* # display image section
		echo render('wddsocial.view.form.pieces.WDDSocial\ImageInputs', array('images' => $content->images));
		
		# display video section
		echo render('wddsocial.view.form.pieces.WDDSocial\VideoInputs', array('videos' => $content->videos)); */
		
		# display category section
		echo render('wddsocial.view.form.pieces.WDDSocial\CategoryInputs', array('categories' => $content->categories));
		
		# display link section
		echo render('wddsocial.view.form.pieces.WDDSocial\LinkInputs', array('links' => $content->links));
		
		#display course section
		if ($_POST['type'] != 'job') {
			echo render('wddsocial.view.form.pieces.WDDSocial\CourseInputs', array('courses' => $content->courses, 'header' => true));
		}
		
		# display other options
		echo render('wddsocial.view.form.pieces.WDDSocial\OtherInputs', array('data' => $content));
		
		# display form footer
		echo render('wddsocial.view.form.create.WDDSocial\BasicElements', array('section' => 'footer'));
		
		# end content section
		echo render(':section', array('section' => 'end_content'));
		
		# display site footer
		echo render(':template', array('section' => 'bottom'));
		
		
		
		
		}
		
		
		
		
	}
	
	
	
	/**
	* Handle content editing
	*/
	
	private function _process_form() {
		import('wddsocial.model.WDDSocial\ContentVO');
		import('wddsocial.model.WDDSocial\FormResponse');
		import('wddsocial.controller.processes.WDDSocial\CategoryProcessor');
		import('wddsocial.controller.processes.WDDSocial\CourseProcessor');
		import('wddsocial.controller.processes.WDDSocial\TeamMemberProcessor');
		import('wddsocial.controller.processes.WDDSocial\LinkProcessor');
		import('wddsocial.controller.processes.WDDSocial\VideoProcessor');
		import('wddsocial.controller.processes.WDDSocial\VanityURLProcessor');
		import('wddsocial.controller.processes.WDDSocial\Uploader');
		
		$this->db = instance(':db');
		$this->sel = instance(':sel-sql');
		
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
		}
		$query->execute(array('id' => $_POST['contentID']));
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ContentVO');
		$content = $query->fetch();
		
		$fields = array();
		
		if ($_POST['title'] != $content->title)
			$fields['title'] = addslashes($_POST['title']);
		
		if ($_POST['description'] != $content->description)
			$fields['description'] = addslashes($_POST['description']);
		
		if ($_POST['content'] != $content->content)
			$fields['content'] = addslashes($_POST['content']);
		
		if ($_POST['vanityURL'] != $content->vanityURL and $_POST['vanityURL'] != '')
			$fields['vanityURL'] = addslashes($_POST['vanityURL']);
		else if ($_POST['vanityURL'] == '')
			VanityURLProcessor::generate($content->id, $content->type);
		
		switch ($content->type) {
			case 'project':
				if ($_POST['completed-date'] != $content->completeDateInput)
					$fields['completeDate'] = addslashes($_POST['completed-date']);
				break;
			case 'event':
				if ($_POST['location'] != $content->location)
					$fields['location'] = addslashes($_POST['location']);
				if ($_POST['duration'] != $content->duration)
					$content->duration = addslashes($_POST['duration']);
				if ($content->duration < 1)
					$content->duration = 1;
				$baseDatetime = addslashes($_POST['date']) . ' ' . addslashes($_POST['start-time']);
				$fields['startDatetime'] = $baseDatetime;
				$fields['endDatetime'] = "DATE_ADD('{$baseDatetime}', INTERVAL {$content->duration} HOUR)";
				break;
			case 'job':
				if ($_POST['company'] != $content->company)
					$fields['company'] = addslashes($_POST['company']);
				if ($_POST['location'] != $content->location)
					$fields['location'] = addslashes($_POST['location']);
				if ($_POST['compensation'] != $content->compensation)
					$fields['compensation'] = addslashes($_POST['compensation']);
				if ($_POST['website'] != $content->website)
					$fields['website'] = addslashes($_POST['website']);
				if ($_POST['email'] != $content->email)
					$fields['email'] = addslashes($_POST['email']);
				break;
		}
		
		
		
		if (count($fields) > 0) {
			$update = array();
			foreach ($fields as $fieldName => $fieldContent) {
				if ($fieldName == 'endDatetime') {
					array_push($update,"endDatetime = $fieldContent");
				}
				else {
					array_push($update,"$fieldName = '$fieldContent'");
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
				/* if ($content->type == 'event') {
					$data = array('id' => $content->id);
					$query = $this->db->prepare($this->sel->getEventICSValues);
					$query->execute($data);
					$query->setFetchMode(\PDO::FETCH_OBJ);
					$event = $query->fetch();
					Uploader::create_ics_file($event);
				} */
			}
		}
		
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
		
		//Uploader::upload_content_images($_FILES['image-files'], $_POST['image-titles'], $contentID, $_POST['title'], $_POST['type']);
		
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
		
		$contentVanityURL = VanityURLProcessor::get($content->id, $content->type);
		
		return new FormResponse(true, "/{$content->type}/{$contentVanityURL}");
	}
}