<?php

class FormView implements \Framework5\IView {		
	
	/**
	* Determines what type of content to render
	*/
	
	public static function render($options = null) {
		
		switch ($options['type']) {
			case 'share':
				return static::share();
			case 'sign_in':
				return static::sign_in();
			default:
				throw new Exception("FormView requires parameter type (share), '{$options['type']}' provided");
		}
	}
	
	
	
	/**
	* Creates the share form
	*/
	
	private static function share() {
		return <<<HTML
<form action="form.html" method="post">
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
	* Creates the sign in form
	*/
	
	private static function sign_in() {
		return <<<HTML
<form action="#" method="post">
						<fieldset>
							<label for="email">Email</label>
							<input type="email" name="email" id="email" />
						</fieldset>
						<fieldset>
							<label for="password" class="helper-link">Password</label>
							<p class="helper-link"><a href="#" title="Did you forget your password?" tabindex="1000">Forgot?</a></p>
							<input type="password" name="password" id="password" />
						</fieldset>
						<p class="helper-link"><a href="form.html" title="Not yet a member of WDD Social? Sign up here." tabindex="1000">Not yet a member?</a></p>
						<input type="submit" value="Sign In" />
					</form>
HTML;
	}
}