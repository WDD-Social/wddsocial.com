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
	* Creates user intro, with name and background information
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
	
	
	
	/**
	* Creates user intro sentence
	*/
	
	private static function getUserIntro($user){
		$root = \Framework5\Request::root_path();
		$sentence = (\WDDSocial\UserValidator::is_current($user->id))?"<strong>You</strong> are a":"<strong>{$user->firstName}</strong> is a";
		
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
			case 'Teacher':
				if(isset($user->extra['courses']) && count($user->extra['courses']) > 0){
					$sentence .= " who teaches";
					for($i = 0; $i < count($user->extra['courses']); $i++){
						if($i == count($user->extra['courses'])-1){
							$sentence .= " and <strong><a href=\"{$root}course/{$user->extra['courses'][$i][id]}\" title=\"{$user->extra['courses'][$i][title]}\">{$user->extra['courses'][$i][id]}</a></strong>";
						}else{
							$sentence .= " <strong><a href=\"{$root}course/{$user->extra['courses'][$i][id]}\" title=\"{$user->extra['courses'][$i][title]}\">{$user->extra['courses'][$i][id]}</a></strong>,";
						}
					}
				}
				break;
			case 'Alum':
				if(isset($user->extra['graduationDate'])){
					$sentence .= " who graduated in <strong>{$user->extra['graduationDate']}</strong>";
				}
				if(isset($user->extra['employerTitle'])){
					$employerLink = (isset($user->extra['employerLink']))?'http://'.$user->extra['employerLink']:"http://www.google.com/search?q={$user->extra['employerTitle']}";
					if(isset($user->extra['graduationDate'])){
						$sentence .= ", and";
					}else{
						$sentence .= " who";
					}
					$sentence .= " works for <strong><a href=\"$employerLink\" title=\"{$user->extra['employerTitle']}\">{$user->extra['employerTitle']}</a></strong>";
				}
				break;
		}
		$sentence .= ".";
		return $sentence;
	}
	
	
	
	/**
	* Creates user contact info section
	*/
	
	private static function contact($user){
		$root = \Framework5\Request::root_path();
		$complete = 0;
		$total = 0;
		foreach($user->contact as $contact){
			$total++;
			if(isset($contact)){
				$complete++;
			}
		}
		$percentage = $complete/$total;
		
		$ownership = \WDDSocial\NaturalLanguage::ownership($user->id,"{$user->firstName} {$user->lastName}"); 
		
		# Create section header
		$html = <<<HTML

				<section id="contact" class="small no-margin with-secondary">
					<h1>Contact</h1>
HTML;
		
		# Create secondary
		if(\WDDSocial\UserValidator::is_current($user->id)){
			$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit Your Contact Information" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
		}
		
		$html .= <<<HTML

					<ul>
						<li>
							<a href="{$root}messages/{$user->vanityURL}" title="Send {$user->firstName} a message">
							<img src="{$root}images/site/icon-contact-mail.png" alt="Send {$user->firstName} a message" />
							<p><strong>WDD Social</strong> Send {$user->firstName} a message</p>
							</a>
						</li>
HTML;
		
		if(isset($user->contact['website'])){
			$html .= <<<HTML

						<li>
							<a href="http://{$user->contact['website']}" title="Visit $ownership Website">
							<img src="{$root}images/site/icon-contact-world.png" alt="Visit $ownership Website" />
							<p><strong>Website</strong> {$user->contact['website']}</p>
							</a>
						</li>
HTML;
		}
		
		if(isset($user->contact['twitter'])){
			$html .= <<<HTML

						<li>
							<a href="http://twitter.com/{$user->contact['twitter']}" title="Visit $ownership Twitter Profile">
							<img src="{$root}images/site/icon-contact-twitter.png" alt="Visit $ownership Twitter Profile" />
							<p><strong>Twitter</strong> @{$user->contact['twitter']}</p>
							</a>
						</li>
HTML;
		}
		
		if(isset($user->contact['facebook'])){
			$html .= <<<HTML

						<li>
							<a href="http://facebook.com/{$user->contact['facebook']}" title="Visit $ownership Facebook Profile">
							<img src="{$root}images/site/icon-contact-facebook.png" alt="Visit $ownership Facebook Profile" />
							<p><strong>Facebook</strong> facebook.com/{$user->contact['facebook']}</p>
							</a>
						</li>
HTML;
		}
		
		if(isset($user->contact['github'])){
			$html .= <<<HTML

						<li>
							<a href="http://github.com/{$user->contact['github']}" title="Visit $ownership Github Profile">
							<img src="{$root}images/site/icon-contact-github.png" alt="Visit $ownership Github Profile" />
							<p><strong>Github</strong> github.com/{$user->contact['github']}</p>
							</a>
						</li>
HTML;
		}
		
		if(isset($user->contact['dribbble'])){
			$html .= <<<HTML

						<li>
							<a href="http://dribbble.com/{$user->contact['dribbble']}" title="Visit $ownership Dribbble Profile">
							<img src="{$root}images/site/icon-contact-dribbble.png" alt="Visit $ownership Dribbble Profile" />
							<p><strong>Dribbble</strong> dribbble.com/{$user->contact['dribbble']}</p>
							</a>
						</li>
HTML;
		}
		
		if(isset($user->contact['forrst'])){
			$html .= <<<HTML

						<li>
							<a href="http://forrst.com/{$user->contact['forrst']}" title="Visit $ownership Forrst Profile">
							<img src="{$root}images/site/icon-contact-forrst.png" alt="Visit $ownership Forrst Profile" />
							<p><strong>Forrst</strong> forrst.com/{$user->contact['forrst']}</p>
							</a>
						</li>
HTML;
		}
		
		$html .= <<<HTML

					</ul>
HTML;
		
		if(\WDDSocial\UserValidator::is_current($user->id) && $percentage < .5){
			$html .= <<<HTML

					<p class="incomplete extra-spacing">People want to talk to you, but they need to know how! Why don&rsquo;t you <strong><a href="{$root}account" title="">add some contact info?</a></strong></p>
HTML;
		}
				
		# Create section footer
		$html .= <<<HTML

				</section><!-- END CONTACT -->
HTML;
		return $html;
	}
}