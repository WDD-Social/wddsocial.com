<?php

/*
* WDD Social: Language Pack for view.UserView
*/

class UserLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			
			#
			# intro()
			#
			
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
			
			
			case '':
				return '';
			case '':
				return '';
			case '':
				return '';
			case '':
				return '';
			
			
			
		}
	}
}