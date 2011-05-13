<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class AccountPage implements \Framework5\IExecutable {
	
	private $user;
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.UserPageLang');
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
	}
	
	
	
	public function execute() {
		
		# get the request user
		$this->user = $this->get_user($_SESSION['user']->id);
		
		if (isset($_POST['submit'])) {
			$this->_process_form();
			return true;
		}
		
		# display site header
		echo render(':template', array('section' => 'top', 'title' => 'My Account'));
			
		# open content section
		echo render(':section', array('section' => 'begin_content'));
		
		# display account form
		echo render('wddsocial.view.form.WDDSocial\AccountView', array('user' => $this->user, 'error' => $errorMessage));
			
		# end content section
		echo render(':section', array('section' => 'end_content'));
		
		# display site footer
		echo render(':template', array('section' => 'bottom'));
		
		$this->_dump_user();
	}
	
	
	
	private function _process_form(){
		echo "<h1>POST:</h1>";
		
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
		
		echo "<h1>USER:</h1>";
		
		echo "<pre>";
		print_r($this->user);
		echo "</pre>";
	}
	
	
	
	private function _dump_user(){
		echo "<pre>";
		print_r($this->user);
		echo "</pre>";
	}
	
	
	
	/**
	* Gets the user and data
	*/
	
	private function get_user($id){
		
		import('wddsocial.model.WDDSocial\UserVO');
		
		# query
		$data = array('id' => $id);
		$query = $this->db->prepare($this->sql->getUserByID);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
		$query->execute($data);
		return $query->fetch();
	}
}