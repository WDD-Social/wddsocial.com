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
			INSERT INTO fw5_bugs (request_id, user_id, timestamp, message)
			VALUES (:request_id, :user_id, :timestamp, :message)";
		$core_db = instance('core.controller.Framework5\Database');
		$query = $core_db->prepare($sql);
		$query->execute(array(
			'request_id' => $request_id,
			'user_id' => $user_id,
			'message' => $_POST['message'],
			'timestamp' => time()));
		
		# get user info
		$site_db = instance(':db');
		$sel_sql = instance(':sel-sql');
		$query = $site_db->prepare($sel_sql->getUserByID);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute(array('id' => $user_id));
		$user = $query->fetch();
		
		# send notification email
		import('wddsocial.controller.WDDSocial\Mailer');
		$mailer = new Mailer();
		$mailer->add_recipient('Social Feedback', 'feedback@wddsocial.com');
		$mailer->subject = "WDD Social Issue Reported";
		$mailer->message = render("wddsocial.view.email.WDDSocial\FeedbackEmail", 
			array('name' => "{$user->firstName} {$user->lastName}", 
				  'email' => $user->email, 
				  'message' => $_POST['message']));
		$mailer->send();
		
		return true;
	}
}