<?php

/*
* WDD Social: Language Pack for view.content.CommentDisplayView
*/

class CommentDisplayLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'edit':
				return 'Edit';
			
			case 'edit_title':
				return 'Edit Your Comment';
			
			case 'delete':
				return 'Delete';
			
			case 'delete_title':
				return 'Delete Your Comment';
			
			case 'flag_user_comment':
				return "Flag $var Comment";
			
			case 'flag':
				return 'Flag';
			
			case 'no_comments':
				return 'No one has commented yet, why don&rsquo;t you start the conversation?';
			
			case 'signin_required':
				return 'You must be signed in to add a comment.';
			
			case 'signin_title':
				return 'Sign In to WDD Social';
				
			case 'signin_link':
				return 'Would you like to sign in?';
		}		
	}
}