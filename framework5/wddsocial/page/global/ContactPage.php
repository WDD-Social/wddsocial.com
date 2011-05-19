<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class ContactPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.ContactPageLang');
	}
	
	
	
	public function execute() {
		
		# handle form submit
		if (isset($_POST['submit'])) {
			$this->_process_form();
		}
		
		# begin content section
		$html = render(':section', array('section' => 'begin_content'));
		
		# display contact form
		$html .= render('wddsocial.view.form.WDDSocial\ContactView');
		
		# end content section
		$html.= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', 
			array('title' => 'Contact Us', 'content' => $html));
		
	}
	
	
	private function _process_form() {
		
		# filter input variables
		$name = filter_input(INPUT_POST, 'name');
		$email = filter_input(INPUT_POST, 'email');
		$message = filter_input(INPUT_POST, 'message');
		
		# validate input
		if (!$name) {
			$this->errorMessage = "Please enter your name, we'd love to know who you are";
			return false;
		}
		if (!$email) {
			$this->errorMessage = "Please enter your email address ";
			return false;
		}
		if (!$message) {
			$this->errorMessage = "Please enter a message";
			return false;
		}
		
		# send email
		import('wddsocial.controller.WDDSocial\Mailer');
		$mailer = new Mailer();
		$mailer->add_recipient('Social Feedback', 'feedback@wddsocial.com');
		$mailer->subject = "WDD Social Feedback";
		$mailer->message = render("wddsocial.view.email.WDDSocial\FeedbackEmail", 
			array('name' => $name, 'email' => $email, 'message' => $message));
		$mailer->send();
		
		
		return true;
	}
}