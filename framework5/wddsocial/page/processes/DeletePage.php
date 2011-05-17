<?php

namespace WDDSocial;

/*
* 
* Author: Anthony Colangelo (me@acolangelo.com)
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class DeletePage implements \Framework5\IExecutable {
	
	public function execute() {
		UserSession::protect();
		
		$this->db = instance(':db');
		$this->sel = instance(':sel-sql');
		$this->val = instance(':val-sql');
		$this->admin = instance(':admin-sql');
		
		$types = array('project','article','event','job','user');
		$type = \Framework5\Request::segment(1);
		$vanityURL = \Framework5\Request::segment(2);
		if (!in_array($type, $types) or !isset($vanityURL))
			redirect('/');
		
		if ($type = 'user')
			import('wddsocial.model.WDDSocial\ContentVO');
		else
			import('wddsocial.model.WDDSocial\UserVO');
		
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
			default:
				redirect('/');
				break;
		}
	}
}