<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SearchPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.SearchPageLang');
	}
	
	
	
	public function execute() {
		if (isset($_POST['term']) and $_POST['term'] != '') {
			redirect("/search/{$_POST['term']}");
		}
		$term = \Framework5\Request::segment(1);
		
		$content .= render(':section', array('section' => 'begin_content'));
		
		$content .= "<h1 class=\"mega\">Search results for &ldquo;$term&rdquo;</h1>";
		
		$content .= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', 
			array('title' => $this->lang->text('page-title'), 'content' => $content));
	}
}