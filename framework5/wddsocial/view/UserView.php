<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class UserView implements \Framework5\IView {
	
	/**
	* Determines what type of content to render
	*/
	
	public static function render($options = null) {
		import('wddsocial.helper.WDDSocial\NaturalLanguage');
		
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
		$html = <<<HTML

				<section id="user" class="mega with-secondary">
					<h1>{$user->firstName} {$user->lastName}</h1>
HTML;
		
		if(\WDDSocial\UserValidator::is_current($user->id)){
			$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit Your Profile" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
		}
		
		$html .= <<<HTML
					
					<img src="{$root}/images/avatars/{$user->avatar}_full.jpg" alt="{$user->firstName} {$user->lastName}" />
					<p><strong>{$user->firstName}</strong> is a <strong>{$user->age}&dash;year&dash;old, {$user->extra['location']} student</strong> from <strong>{$user->hometown}</strong> who began Full Sail  in <strong>{$user->extra['startDate']}</strong>.</p>
					<div class="large">
						<h2>Bio</h2>
						<p>{$user->bio}</p>
					</div><!-- END BIO -->
					<div class="small">
						<h2>Likes</h2>
						<ul>
HTML;
		foreach($user->extra['likes'] as $like){
			$html .= <<<HTML

							<li><a href="{$root}/search/$like" title="$like">$like</a></li>
HTML;
		}
		$html .= <<<HTML

						</ul>
					</div><!-- END LIKES -->
					<div class="small no-margin">
						<h2>Dislikes</h2>
						<ul>
HTML;
		foreach($user->extra['dislikes'] as $dislike){
			$html .= <<<HTML

							<li><a href="{$root}/search/$dislike" title="$dislike">$dislike</a></li>
HTML;
		}
		$html .= <<<HTML

						</ul>
					</div><!-- END DISLIKES -->
					
					<!-- COMPLETE PROFILE PROMPT -->
					<p class="incomplete">Hmm, your profile seems to be missing a few things, would you like to <strong><a href="form.html" title="">complete it now?</a></strong></p>
				</section><!-- END USER -->
HTML;
		return $html;
	}
}