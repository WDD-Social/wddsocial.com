<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class AboutPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.AboutPageLang');
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
	}
	
	
	
	public function execute() {
		$content = " ";
		
		$users = array();
		import('wddsocial.model.WDDSocial\UserVO');
		$query = $this->db->query($this->sql->getAboutUs);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
		while ($user = $query->fetch()) {
			array_push($users, $user);
		}
		
		$content .= render(':section', array('section' => 'begin_content'));
		$content .=	render('wddsocial.view.page.WDDSocial\AboutView',array('users' => $users));
		$content .= render(':section', array('section' => 'end_content'));
		
		echo render(':template', 
			array('title' => $this->lang->text('page-title'), 'content' => $content));
	}
}