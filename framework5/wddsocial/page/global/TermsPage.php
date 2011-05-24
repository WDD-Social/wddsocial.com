<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class TermsPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.TermsPageLang');
	}
	
	
	
	public function execute() {
		$content .= render(':section', array('section' => 'begin_content'));
		
		$content .= render('wddsocial.view.page.WDDSocial\TermsView');
		
		$content .= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', 
			array('title' => $this->lang->text('page-title'), 'content' => $content));
	}
}