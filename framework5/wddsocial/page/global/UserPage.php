<?php

namespace WDDSocial;

/*
* Sample script 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class UserPage implements \Framework5\IExecutable {
	
	public static function execute() {	
		
		$user = static::getUser(\Framework5\Request::segment(1));
		if($user == false){
			echo render('wddsocial.view.WDDSocial\TemplateView', 
				array('section' => 'top', 'title' => "User Not Found"));
			echo "<h1>User Not Found</h1>";
			echo render('wddsocial.view.WDDSocial\TemplateView', 
				array('section' => 'bottom'));
		}else{
			# display site header
			echo render('wddsocial.view.WDDSocial\TemplateView', 
				array('section' => 'top', 'title' => "{$user->firstName} {$user->lastName}"));
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
			/* echo render('wddsocial.view.WDDSocial\UserView', array('section' => 'intro', 'user' => $user)); */
			echo "<pre>";
			print_r($user);
			echo "</pre>";
			
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'end_content'));
			
			# display site footer
			echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'bottom'));
		}
	}
	
	/**
	* Gets the user and data
	*/
	
	private static function getUser($vanityURL){
		import('wddsocial.model.WDDSocial\UserVO');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$data = array('vanityURL' => $vanityURL);
		$query = $db->prepare($sql->getUserByVanityURL);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
		$query->execute($data);
		return $query->fetch();
	}
}