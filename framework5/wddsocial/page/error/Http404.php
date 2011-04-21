<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class Http404 implements \Framework5\IExecutable {

	public static function execute() {
		echo render('wddsocial.view.TemplateView', array('section' => 'top', 'title' => '404: Page not found'));
		echo render('wddsocial.view.SectionView', array('section' => 'begin_content'));
		echo render('wddsocial.view.Http404View');
		echo render('wddsocial.view.SectionView', array('section' => 'end_content'));
		echo render('wddsocial.view.TemplateView', array('section' => 'bottom'));
	}
}