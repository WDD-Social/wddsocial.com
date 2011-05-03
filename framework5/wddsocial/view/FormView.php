<?php

namespace WDDSocial;

class FormView implements \Framework5\IView {
	
	/**
	* Determines what type of content to render
	*/
	
	public static function render($options = null) {
		
		switch ($options['type']) {
			case 'share':
				return static::share();
			case 'sign_up':
				return static::sign_up($options['error']);
			case 'sign_up_intro':
				return static::sign_up_intro(); 
			case 'comment':
				return static::comment(); 
			default:
				throw new \Framework5\Exception("FormView requires parameter type (share, sign_up, sign_up_intro, or comment), '{$options['type']}' provided");
		}
	}
	
	
	
	/**
	* Creates the share form
	*/
	
	private static function share() {
		$root = \Framework5\Request::root_path();
		return <<<HTML

					<form action="{$root}" method="post">
						<p class="error"><strong></strong></p>
						<fieldset>
							<label for="title">Title</label>
							<input type="text" name="title" id="title" />
						</fieldset>
						<fieldset class="radio">
							<label>Type</label>
							<div>
								<input type="radio" id="project" name="type" checked />
								<label for="project">Project</label>
								
								<input type="radio" id="article" name="type" />
								<label for="article">Article</label>
								
								<input type="radio" id="event" name="type" />
								<label for="event">Event</label>
								
								<input type="radio" id="job" name="type" />
								<label for="job">Job</label>
							</div>
						</fieldset>
						<input type="submit" value="Create" />
					</form>
HTML;
	}
	
	
	
	/**
	* 
	*/
	
	private static function sign_up_intro(){
		return <<<HTML

					<h1 class="mega form">Join the community. Socialize.</h1>
					<h2 class="form">* Required</h2>
HTML;
	}
	
	
	
	/**
	* Creates the sign up form
	*/
	
	private static function sign_up($error) {
		$root = \Framework5\Request::root_path();
		return <<<HTML

					<form action="{$root}signup" method="post" enctype="multipart/form-data">
					<h1>Basic</h1>
					<p class="error"><strong>$error</strong></p>
					<fieldset>
						<label for="first-name">First Name *</label>
						<input type="text" name="first-name" id="first-name" />
					</fieldset>
					<fieldset>
						<label for="last-name">Last Name *</label>
						<input type="text" name="last-name" id="last-name" />
					</fieldset>
					<fieldset>
						<label for="email">Email *</label>
						<input type="email" name="email" id="email" />
					</fieldset>
					<fieldset>
						<label for="full-sail-email">Full Sail Email *</label>
						<input type="email" name="full-sail-email" id="full-sail-email" />
						<small>Used for account verification</small>
					</fieldset>
					<fieldset>
						<label for="password">Password *</label>
						<input type="password" name="password" id="password" />
						<small>6 or more characters</small>
					</fieldset>
					
					<h1>Background</h1>
					<fieldset class="radio">
						<label>I am a...*</label>
						<div>
							<input type="radio" id="student" name="user-type" value="student" checked />
							<label for="student">Student</label>
							
							<input type="radio" id="teacher" name="user-type" value="teacher" />
							<label for="teacher">Teacher</label>
							
							<input type="radio" id="alum" name="user-type" value="alum" />
							<label for="alum">Alum</label>
						</div>
					</fieldset>
					<fieldset>
						<label for="avatar">Avatar</label>
						<input type="file" name="avatar" id="avatar" />
					</fieldset>
					<fieldset>
						<label for="hometown">Hometown</label>
						<input type="text" name="hometown" id="hometown" />
					</fieldset>
					<fieldset>
						<label for="birthday">Birthday</label>
						<input type="text" name="birthday" id="birthday" />
					</fieldset>
					<fieldset>
						<label for="bio">Bio</label>
						<textarea id="bio"></textarea>
						<small><span class="count">255</span> characters left</small>
					</fieldset>
					
					<fieldset class="terms">
						<label>Boring Legal Stuff *</label>
						<p><input type="checkbox" name="terms" id="terms" />I have read and agree to the <a href="{$root}terms" title="WDD Social Terms of Service">Terms of Service</a>.</p>
					</fieldset>
					<p class="helper-link"><a href="{$root}signin" title="Already a WDD Social member?">Already a member?</a></p>
					<input type="hidden" name="process" value="signup" />
					<input type="submit" value="Sign Up" />
				</form>
HTML;
	}
	
	
	
	/**
	* Creates the comment form
	*/
	
	private static function comment() {
		$root = \Framework5\Request::root_path();
		return <<<HTML

						<form action="{$root}" method="post">
							<textarea class="placeholder">Write your comment...</textarea>
							<input type="submit" value="Post Comment" />
						</form>
HTML;
	}
}