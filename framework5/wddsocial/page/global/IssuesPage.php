<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class IssuesPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# view package locations
		$reportBugView = 'wddsocial.view.page.WDDSocial\IssueReportView';
		$bugReportedView = 'wddsocial.view.page.WDDSocial\IssueReportedView';
		
		# begin content section
		$content = render(':section', array('section' => 'begin_content'));
		
		# form submitted
		if (isset($_POST['submit'])) {
			if ($this->_process_form()) {
				$content.= render($bugReportedView);
			}
			else {
				$content.= render($reportBugView, $this->errorMsg);
			}
		}
		
		# form not submitted
		else {
			$content.= render($reportBugView);
		}
		
		$content.= render(':section', array('section' => 'begin_content'));
		
		# display page
		echo render(':template', array('title' => "Report an issue", 'content' => $content));
		
	}
	
	
	
	/**
	* Handles form submit
	*/
	
	private function _process_form() {
		
		# get the request id of the page the error was reported on
		$request_id = \Framework5\Request::segment(1);
		
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
		$sql = "
			INSERT INTO fw5_bugs (request_id, message, user_id)
			VALUES (:request_id, :message, :user_id)";
		
		$db = instance('core.controller.Framework5\Database');
		$query = $db->prepare($sql);
		$query->execute(array(
			'request_id' => $request_id,
			'message' => $_POST['message'],
			'user_id' => $user_id));
		
		return true;
	}
}