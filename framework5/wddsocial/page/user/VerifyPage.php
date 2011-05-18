<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class VerifyPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		$verificationCode = \Framework5\Request::segment(1);
		$response = $this->verify_user($verificationCode);
		
		# display site header
		$page_title = 'Verify your account';
		
		# open content section
		$content.= render(':section', array('section' => 'begin_content'));
		
		# success
		if ($response->status) {
			# display header
			$content.= render('wddsocial.view.page.WDDSocial\VerifyView',
				array('type' => 'success'));
			
			# display signin form
			$content.= render('wddsocial.view.form.WDDSocial\SigninView', 
				array('error' => $response->message));
		}
		
		# error
		else {
			# display header
			$content.= render('wddsocial.view.page.WDDSocial\VerifyView',
				array('type' => 'error'));
		}
		
		
		# end content section
		$content.= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render('wddsocial.view.global.WDDSocial\SiteTemplate', 
			array('title' => $page_title, 'content' => $content));
	}
	
	
	private function verify_user($code) {
		
		import('wddsocial.model.WDDSocial\FormResponse');
		
		if (!isset($code))
			return new FormResponse(false, "Must enter verification code");
		
		$db = instance(':db');
		$sel_sql = instance(':sel-sql');
		$admin_sql = instance(':admin-sql');
		
		# check the code
		$query = $db->prepare($sel_sql->getUserByVerificationCode);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute(array('verificationCode' => $code));
		$row = $query->fetch();
		 
		# invalid code
		if ($query->rowCount() == 0)
			return new FormResponse(false, "Invalid verification code");
		
		# verify the user
		$query = $db->prepare($admin_sql->verifyUserByID);
		$query->execute(array('id' => $row->id));
		return new FormResponse(true);
	}
}