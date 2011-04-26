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
				throw new \Framework5\Exception("UserView requires parameter section (intro or contact), '{$options['section']}' provided");
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
		$userIntro = static::getUserIntro($user);
		$html .= <<<HTML
					
					<img src="{$root}/images/avatars/{$user->avatar}_full.jpg" alt="{$user->firstName} {$user->lastName}" />
					<p>$userIntro</p>
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
				</section><!-- END USER -->
HTML;
		return $html;
	}
	
	private static function getUserIntro($user){
		$sentence = "<strong>{$user->firstName}</strong> is a";
		if(isset($user->age)){
			$sentence .= " <strong>{$user->age}-year-old</strong>";
		}
		if($user->type == 'Student'){
			if(isset($user->age)){
				$sentence .= ",";
			}else{
				$sentence .= " an";
			}
			if(isset($user->extra['location'])){
				$sentence .= " <strong>{$user->extra['location']}</strong>";
			}
		}
		$userType = strtolower($user->type);
		$sentence .= " <strong>{$userType}</strong>";
		if(isset($user->hometown)){
			$sentence .= " from <strong>{$user->hometown}</strong>";
		}
		switch ($user->type) {
		    case 'Student':
		        if(isset($user->extra['startDate'])){
		        	$sentence .= " who began Full Sail in <strong>{$user->extra['startDate']}</strong>";
		        }
		        break;
		}
		$sentence .= ".";
		return $sentence;
	}
}