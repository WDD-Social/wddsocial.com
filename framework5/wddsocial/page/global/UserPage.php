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
		if ($user == false){
			
			# display site header
			echo render(':template', array('section' => 'top', 'title' => "User Not Found"));
			echo render(':section', array('section' => 'begin_content'));
			
			# display user not found view
			echo render('wddsocial.view.profile.WDDSocial\NotFoundView');
		}
		
		# if the user exists
		else {
			
			# display site header
			echo render(':template', 
				array('section' => 'top', 'title' => "{$user->firstName} {$user->lastName}"));
			
			# display begin content
			echo render(':section', array('section' => 'begin_content'));
			
			# display user intro
			echo render('wddsocial.view.profile.WDDSocial\UserIntroView', $user);
			
			# display user's latest activity
			static::getUserLatest($user->id);
			
			# display users' contact info
			echo render('wddsocial.view.profile.WDDSocial\UserContactView', $user);
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
		
		# Create section header
		echo render(':section',
			array('section' => 'begin_content_section', 'id' => 'latest',
				'classes' => array('medium', 'with-secondary', 'filterable'),
				'header' => 'Latest', 'extra' => 'user_latest_filters'));
		
		# Create section items
		while ($row = $query->fetch()) {
			echo render('wddsocial.view.WDDSocial\MediumDisplayView', 
				array('type' => $row->type,'content' => $row));
		}
		
		# Create section footer
		echo render(':section',
			array('section' => 'end_content_section', 'id' => 'latest', 'load_more' => 'posts'));
	}
}