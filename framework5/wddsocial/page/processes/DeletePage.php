<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class DeletePage implements \Framework5\IExecutable {
	public function execute() {
		UserSession::protect();
		$types = array('project','article','event','job','user');
		$type = \Framework5\Request::segment(1);
		$vanityURL = \Framework5\Request::segment(2);
		if (!in_array($type, $types) or !isset($vanityURL))
			redirect('/');
		
		switch ($type) {
			case 'project':
				
				break;
			case 'article':
				
				break;
			case 'event':
				
				break;
			case 'job':
				
				break;
			case 'user':
				
				break;
		}
	}
}