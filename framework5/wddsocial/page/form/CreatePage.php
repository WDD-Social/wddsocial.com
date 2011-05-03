<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class CreatePage implements \Framework5\IExecutable {
	
	public static function execute() {
		# display site header
		echo render('wddsocial.view.WDDSocial\TemplateView', 
			array('section' => 'top', 'title' => "Create new {$_POST['type']}"));
		
		# open content section
		echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
		
		echo render('wddsocial.view.form.create.WDDSocial\BasicElements', array('section' => 'header', 'data' => $_POST));
		
		/*
echo "<pre>";
		print_r($_POST);
		echo "</pre>";
*/
		
		echo render('wddsocial.view.form.create.WDDSocial\BasicElements', array('section' => 'footer'));
		
		# end content section
		echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'end_content'));
		
		# display site footer
		echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'bottom'));
	}
}