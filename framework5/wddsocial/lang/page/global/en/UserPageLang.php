<?php

/*
* WDD Social: Language Pack for view.UserView
*/

class UserPageLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'not-found-page-title':
				return 'User Not Found';
			
			case 'edit':
				return 'Edit';
			
			case 'edit_your_profile':
				return 'Edit Your Profile';
			
			case 'bio':
				return 'Bio';
			
			case 'likes':
				return 'Likes';
			
			case 'dislikes':
				return 'Dislikes';
			
			case 'latest':
				return 'Latest';
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}