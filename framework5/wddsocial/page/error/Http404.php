<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class Http404 implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.error.Http404Lang');
	}
	
	
	
	public function execute() {
		
		$content = render(':section', array('section' => 'begin_content'));
		$content.= render('wddsocial.view.page.WDDSocial\Http404View');
		$content.= render(':section', array('section' => 'end_content'));
		
		echo render(':template', 
			array('title' => $this->lang->text('page-title'), 'content' => $content));
	}
}