<?php

namespace WDDSocial;

/*
* 
* Author: Anthony Colangelo (me@acolangelo.com)
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class EditPageDebug implements \Framework5\IExecutable {
	
	public function execute() {
		UserSession::protect();
		
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
		
		$this->db = instance(':db');
		$this->sel = instance(':sel-sql');
		$this->val = instance(':val-sql');
		$this->admin = instance(':admin-sql');
		
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
		
		# display image section
		echo render('wddsocial.view.form.pieces.WDDSocial\ImageInputs', array('images' => $content->images));
		
		# display video section
		echo render('wddsocial.view.form.pieces.WDDSocial\VideoInputs', array('videos' => $content->videos));
		
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
			$fields['title'] = $_POST['title'];
		
		if ($_POST['description'] != $content->description)
			$fields['description'] = $_POST['description'];
		
		if ($_POST['content'] != $content->content )
			$fields['content'] = $_POST['content'];
		
		switch ($content->type) {
			case 'project':
				if ($_POST['completed-date'] != $content->completeDateInput )
					$fields['completedDate'] = $_POST['completed-date'];
				break;
			case 'event':
				if ($_POST['location'] != $content->location )
					$fields['location'] = $_POST['location'];
				if ( ($_POST['date'] != $content->startDateInput) or ($_POST['start-time'] != $content->startTimeInput))
					$fields['startDatetime'] = $_POST['date'] . ' ' . $_POST['start-time'];
				if ($_POST['duration'] != $content->duration)
					$fields['duration'] = $_POST['duration'];
				break;
			case 'job':
				if ($_POST['job-type'] != $content->jobTypeID)
					$fields['typeID'] = $_POST['job-type'];
				if ($_POST['company'] != $content->company)
					$fields['company'] = $_POST['company'];
				if ($_POST['location'] != $content->location)
					$fields['location'] = $_POST['location'];
				if ($_POST['compensation'] != $content->compensation)
					$fields['compensation'] = $_POST['compensation'];
				if ($_POST['website'] != $content->website)
					$fields['website'] = $_POST['website'];
				if ($_POST['email'] != $content->email)
					$fields['email'] = $_POST['email'];
				break;
		}
		
		echo "<h1>FIELDS</h1>";
		echo "<pre>";
		print_r($fields);
		echo "</pre>";
		
		echo "<h1>POST</h1>";
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
		
		echo "<h1>CONTENT</h1>";
		echo "<pre>";
		print_r($content);
		echo "</pre>";
	}
}