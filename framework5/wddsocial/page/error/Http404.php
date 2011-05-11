<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class Http404 implements \Framework5\IExecutable {

	public function execute() {
		
		# page header
		echo render(':template', array('section' => 'top', 'title' => '404: Page not found'));
			
		echo render(':section', array('section' => 'begin_content'));
		echo render('wddsocial.view.page.WDDSocial\Http404View');
		echo render(':section', array('section' => 'end_content'));
		
		# page footer
		echo render(':template', array('section' => 'bottom'));
	}
}