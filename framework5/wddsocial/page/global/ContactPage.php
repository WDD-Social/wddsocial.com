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
}