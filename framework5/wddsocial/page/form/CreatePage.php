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
				# redirect user to last page
				/*
if ($_SESSION['last_page']) {
					redirect($_SESSION['last_page']);
					$_SESSION['last_page'] = null;
				}
				else {
					redirect('/');
				}
*/
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
		
		if ($incomplete){
			return new FormResponse(false, "Please complete all required fields.");
		}
		
		switch ($_POST['type']) {
			case 'project':
				$data = array(	'userID' => $_SESSION['user']->id,
								'title' => $_POST['title'],
								'description' => $_POST['description'],
								'content' => $_POST['content'],
								'vanityURL' => $_POST['vanityURL'],
								'completeDate' => $_POST['completed-date']
				);
				$query = $db->prepare($admin_sql->addProject);
				break;
			case 'article':
				$data = array(	'userID' => $_SESSION['user']->id,
								'privacyLevelID' => $_POST['privacy-level'],
								'title' => $_POST['title'],
								'description' => $_POST['description'],
								'content' => $_POST['content'],
								'vanityURL' => $_POST['vanityURL']
				);
				$query = $db->prepare($admin_sql->addArticle);
				break;
			case 'event':
				$startDatetime = $_POST['date'] . ' ' . $_POST['start-time'];
				$data = array(	'userID' => $_SESSION['user']->id,
								'privacyLevelID' => $_POST['privacy-level'],
								'title' => $_POST['title'],
								'description' => $_POST['description'],
								'content' => $_POST['content'],
								'vanityURL' => $_POST['vanityURL'],
								'location' => $_POST['location'],
								'startDatetime' => $startDatetime,
								'duration' => $_POST['duration']
				);
				$query = $db->prepare($admin_sql->addEvent);
				break;
			case 'job':
				$data = array(	'userID' => $_SESSION['user']->id,
								'typeID' => $_POST['job-type'],
								'title' => $_POST['title'],
								'description' => $_POST['description'],
								'content' => $_POST['content'],
								'vanityURL' => $_POST['vanityURL'],
								'company' => $_POST['company'],
								'email' => $_POST['email'],
								'location' => $_POST['location'],
								'website' => $_POST['website'],
								'compensation' => $_POST['compensation']
				);
				$query = $db->prepare($admin_sql->addJob);
				break;
		}
		$query->execute($data);
		
		echo "<pre>";
		echo "<h1>POST:</h1>";
		print_r($_POST);
		echo "<h1>FILES:</h1>";
		print_r($_FILES);
		echo "</pre>";
	}
}