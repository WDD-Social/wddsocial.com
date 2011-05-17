<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class MessagesPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# require authentication to access this page
		UserSession::protect();
		
		# display site header
		echo render('wddsocial.view.global.WDDSocial\SiteTemplate', 
			array('title' => 'Messages', 'content' => "HTMLC"));

		
	}
}