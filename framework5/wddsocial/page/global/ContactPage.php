<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class ContactPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# display site header
		echo render(':template', array('section' => 'top', 'title' => 'Contact'));
		
		# send email
		import('wddsocial.controller.WDDSocial\Mailer');
		$mailer = new Mailer();
		$mailer->add_recipient('Tyler Matthews', 'tyler@wddsocail.com');
		$mailer->add_recipient('Anthony Colangelp', 'anthony@wddsocial.com');
		$mailer->subject = "Subject Line";
		$mailer->message = "Message Content";
		
		if ($mailer->send()) echo "email sent";
		else echo "mail fail";
		
		# display site footer
		echo render(':template', array('section' => 'bottom'));
		
	}
}