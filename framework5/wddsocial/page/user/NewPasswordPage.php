<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class NewPasswordPage implements \Framework5\IExecutable {
	
	public function __construct() {
		//$this->lang = new \Framework5\Lang('wddsocial.lang');
		$this->db = instance(':db');
	}
	
	
	
	public function execute() {
		
		# check verificatino code
		$code = \Framework5\Request::segment(1);
		echo $code;
		
		if (isset($_POST['submit'])) {
			# form success
			if ($this->_process_form()) {
				$intro = "";
				$form = render('wddsocial.view.form.WDDSocial\NewPasswordView', 
					array('intro' => $intro));
			}
			
			# form failure
			else {
				$form = render('wddsocial.view.form.WDDSocial\NewPasswordView', 
					array('error' => $this->errorMsg));
			}
		}
		
		else {
			$intro = "intro-text";
			$form = render('wddsocial.view.form.WDDSocial\NewPasswordView', 
					array('intro' => $intro));
		}
		
		
		
		
		# display site header
		echo render(':template', array('section' => 'top', 'title' => 'Create New Password'));
		
		# open content section
		echo render(':section', array('section' => 'begin_content'));
		
		# display form header
		echo render('wddsocial.view.form.WDDSocial\ExtraView', array('type' => 'new_pass_intro'));
		
		# 
		echo $form;
		
		# end content section
		echo render(':section', array('section' => 'end_content'));
		
		# display site footer
		echo render(':template', array('section' => 'bottom'));
	}
}