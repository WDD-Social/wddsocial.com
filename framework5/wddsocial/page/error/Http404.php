<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class Http404 implements \Framework5\IExecutable {

	public static function execute() {
		
		# page header
		echo render('wddsocial.view.WDDSocial\TemplateView', 
			array('section' => 'top', 'title' => '404: Page not found'));
			
		echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
		echo render('wddsocial.view.WDDSocial\Http404View');
		echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'end_content'));
		
		# page footer
		echo render('wddsocial.view.WDDSocial\TemplateView', 
			array('section' => 'bottom'));
	}
}