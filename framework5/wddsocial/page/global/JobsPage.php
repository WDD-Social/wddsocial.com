<?php

namespace WDDSocial;

/*
*
* @author Anthony Colangelo (me@acolangelo.com) 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class JobsPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.JobsPageLang');
	}
	
	
	
	public function execute() {
		
		$page_title = $this->lang->text('page-title'); # set page title
		
		
		$content = render(':section', array('section' => 'begin_content'));
		$content .= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render('wddsocial.view.global.WDDSocial\SiteTemplate', 
			array('title' => $page_title, 'content' => $content));
	}
}