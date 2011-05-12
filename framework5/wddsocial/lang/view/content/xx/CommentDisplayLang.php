<?php

/*
* WDD Social: Language Pack for view.content.CommentDisplayView
*/

class CommentDisplayLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			case 'edit':
				return 'XX'; # Edit
			
			case 'edit_title':
				return 'XX'; # Edit Your Comment
			
			case 'delete':
				return 'XX'; # Delete
			
			case 'delete_title':
				return 'XX'; # Delete Your Comment
			
			case 'flag_user_comment':
				return "XX $var XX"; # Flag $var Comment
			
			case 'flag':
				return 'XX'; # Flag
			
			case 'no_comments':
				return 'XX'; # No one has commented yet, why don&rsquo;t you start the conversation?
			
			case 'signin_required':
				return 'XX'; # You must be signed in to add a comment.
			
			case 'signin_title':
				return 'XX'; # Sign In to WDD Social
			
			case 'signin_link':
				return 'XX'; # Would you like to sign in?
		}		
	}
}