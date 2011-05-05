<?php

namespace WDDSocial;

/*
* User Profile Page
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class UserPage implements \Framework5\IExecutable {
	
	
	
	public static function execute() {	
		
		# get the request user
		$user = static::getUser(\Framework5\Request::segment(1));
		
		# if the user does not exist
		if ($user) {
			
			# display site header
			echo render(':template', 
				array('section' => 'top', 'title' => "{$user->firstName} {$user->lastName}"));
			echo render(':section', array('section' => 'begin_content'));
			
			# display user intro
			echo render('wddsocial.view.profile.WDDSocial\UserIntroView', $user);
			
			# display section header
			echo render(':section',
				array('section' => 'begin_content_section', 'id' => 'latest',
					'classes' => array('medium', 'with-secondary', 'filterable'),
					'header' => 'Latest', 'extra' => 'user_latest_filters'));
			
			# display section items
			$activity = static::getUserLatest($user->id);
			foreach ($activity as $row) {
				echo render('wddsocial.view.WDDSocial\MediumDisplayView', 
					array('type' => $row->type,'content' => $row));
			}
			
			# display section footer
			echo render(':section',
				array('section' => 'end_content_section', 'id' => 'latest', 'load_more' => 'posts'));
					
			# display users' contact info
			echo render('wddsocial.view.profile.WDDSocial\UserContactView', $user);
		}
		
		
		else {
			
			# display site header
			echo render(':template', array('section' => 'top', 'title' => "User Not Found"));
			echo render(':section', array('section' => 'begin_content'));
			
			# display user not found view
			echo render('wddsocial.view.profile.WDDSocial\NotFoundView');
		}
		
		
		# display site footer
		echo render(':section', array('section' => 'end_content'));
		echo render(':template', array('section' => 'bottom'));
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
	
	
	
	/**
	* Gets latest activity relating to user
	*/
	
	private static function getUserLatest($id){
		
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$data = array('id' => $id);
		$query = $db->prepare($sql->getUserLatest);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		$query->execute($data);
		
		return $query->fetchAll();
	}
}