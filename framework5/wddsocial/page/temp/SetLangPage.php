<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SetLangPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.AboutPageLang');
	}
	
	
	
	public function execute() {
				
		$lang = \Framework5\Request::segment(1);
		if (set($lang)) {
			$_SESSION['lang'] = $lang;
			$message = "Language set to '$lang'";
		}
		
		$content = <<<HTML
			<p>{$message}</p>
			<a href="/lang/en">en</a> | <a href="/lang/xx">xx</a> | 
			<a href="/lang/fr">fr</a> | <a href="/lang/es">es</a>
HTML;
		
		echo render(':template', 
			array('title' => "Set Language", 'content' => $content));
	}
}