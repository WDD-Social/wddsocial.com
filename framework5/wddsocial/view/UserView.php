<?php

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class UserView implements \Framework5\IView {
	
	/**
	* Determines what type of content to render
	*/
	
	public static function render($options = null) {
		import('wddsocial.helper.NaturalLanguage');
		
		# retrieve content based on the provided section
		switch ($options['section']) {
			case 'intro':
				return static::intro($options['user']);
			case 'contact':
				return static::contact($options['user']);
			default:
				throw new Exception("UserView requires parameter section (intro or contact), '{$options['section']}' provided");
		}
	}
	
	
	
	/**
	* Opens main content section, with optional classes
	*/
	
	private static function intro($user){
		$userDisplayName = \WDDSocial\NaturalLanguage::display_name($user->id,"{$user->firstName} {$user->lastName}");
		
		$html = <<<HTML

				<section id="user" class="mega with-secondary">
					<h1>$userDisplayName</h1>
HTML;
		
		if(\WDDSocial\UserValidator::is_current($user->id)){
			$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit Your Profile" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
		}
		
		$html .= <<<HTML
					
					<img src="{$root}/images/avatars/{$user->avatar}_full.jpg" alt="$userDisplayName" />
					<p><strong>{$user->firstName}</strong> is a <strong>{$user->age}&dash;year&dash;old, on&dash;campus student</strong> from <strong>{$user->hometown}</strong> who began Full Sail  in <strong>August, 2009</strong>.</p>
					<div class="large">
						<h2>Bio</h2>
						<p>{$user->bio}</p>
					</div><!-- END BIO -->
					<div class="small">
						<h2>Likes</h2>
						<ul>
							<li><a href="#" title="Categories | HTML5">HTML5</a></li>
							<li><a href="#" title="Categories | CSS3">CSS3</a></li>
							<li><a href="#" title="Categories | JavaScript/jQuery">JavaScript/jQuery</a></li>
							<li><a href="#" title="Categories | PHP 5">PHP 5</a></li>
							<li><a href="#" title="Categories | SQL">SQL</a></li>
							<li><a href="#" title="Categories | OOP">OOP</a></li>
						</ul>
					</div><!-- END LIKES -->
					<div class="small no-margin">
						<h2>Dislikes</h2>
						<ul>
							<li><a href="#" title="Categories | Internet Explorer">Internet Explorer</a></li>
							<li><a href="#" title="Categories | Table&dash;based design">Table&dash;based design</a></li>
						</ul>
					</div><!-- END DISLIKES -->
					
					<!-- COMPLETE PROFILE PROMPT
					<p class="incomplete">Hmm, your profile seems to be missing a few things, would you like to <strong><a href="form.html" title="">complete it now?</a></strong></p> -->
				</section><!-- END USER -->
HTML;
		return $html;
	}
}