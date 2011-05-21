<?php

/*
* WDD Social: Language Pack for view.UserView
*/

class UserPageLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'edit':
				return 'XX'; # Edit
			
			case 'edit_your_profile':
				return 'XX'; # Edit Your Profile
			
			case 'bio':
				return 'XX'; # Bio
			
			case 'likes':
				return 'XX'; # Likes
			
			case 'dislikes':
				return 'XX'; # Dislikes
			
			case 'latest':
				return 'XX'; # Latest
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}