<?php

namespace Framework5\Bugs;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class ReportPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# form submitted
		if (isset($_POST['submit'])) {
			if ($this->_process_form()) {
				$page = render('bugs.view.page.Framework5\Bugs\BugReportedView');
			}
			else {
				$page = render('bugs.view.page.Framework5\Bugs\ReportBugView', $this->errorMsg);
			}
		}
		
		# form not submitted
		else {
			$page = render('bugs.view.page.Framework5\Bugs\ReportBugView');
		}
		
		# display page
		echo render(':template', $page);
	}
	
	
	private function _process_form() {
		
		# get the request id of the page the error was reported on
		$request_id = \Framework5\Request::segment(2);
		
		# start wddsocial user session
		import('wddsocial.controller.WDDSocial\UserSession');
		\WDDSocial\UserSession::init();
		
		# get the user id, if user was logged in
		if (\WDDSocial\UserSession::is_authorized()) {
			$user_id = \WDDSocial\UserSession::userid();
		}
		
		# validate user input
		if (!isset($_POST['message'])) {
			$this->errorMsg = 'Please describe the problem your having';
			return false;
		}
		
		# insert bug into db
		$db = instance(':db');
		
		$query = $db->prepare(
			"INSERT INTO fw5_bugs (request_id, message, user_id) VALUES (?, ?, ?);");
		
		$data = array(
			'request_id' => $request_id,
			'message' => $_POST['message'],
			'user_id' => $user_id);
		
		$query->execute($data);
		
		return true;
	}
}