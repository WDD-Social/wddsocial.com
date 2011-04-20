<?php

class FormView implements \Framework5\IView {		
	
	/**
	* Determines what type of content to render
	*/
	
	public static function render($options = null) {
		
		switch ($options['type']) {
			case 'share':
				return static::share();
			case 'signin_home':
				return static::signin_home();
			default:
				throw new Exception("FormView requires parameter type (share), '{$options['type']}' provided");
		}
	}
	
	
	
	/**
	* Creates the share form
	*/
	
	private static function share() {
		return <<<HTML

				<section id="share" class="small no-margin side-sticky">
					<h1>Share</h1>
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
				</section><!-- END SHARE -->
HTML;
	}
	
	
	
	/**
	* Creates the share form
	*/
	
	private static function signin_home() {
		return <<<HTML

				<section id="sign-in" class="small no-margin">
					<h1>Sign In</h1>
					<form action="dashboard.html" method="post">
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
				</section><!-- END SIGN IN -->
HTML;
	}
}