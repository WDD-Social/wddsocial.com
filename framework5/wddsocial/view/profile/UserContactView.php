<?php

namespace WDDSocial;

/*
* Displays a users contact methods on their profile
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class UserContactView implements \Framework5\IView {
	
	/**
	* Render View
	*/
	
	public function render($user = null) {
		
		if (!isset($user)) {
			throw new Exception("UserContactView required option 'user' was not set");
		}
		
		$root = \Framework5\Request::root_path();
		$complete = 0;
		$total = 0;
		
		foreach ($user->contact as $contact) {
			$total++;
			if(isset($contact)){
				$complete++;
			}
		}
		
		$percentage = $complete/$total;
		
		$ownership = NaturalLanguage::ownership($user->id,"{$user->firstName} {$user->lastName}"); 
		
		# Create section header
		$html = <<<HTML

				<section id="contact" class="small no-margin with-secondary">
					<h1>Contact</h1>
HTML;
		
		# Create secondary
		if(UserSession::is_current($user->id)){
			$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit Your Contact Information" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
		}
		
		$html .= <<<HTML

					<ul>
HTML;
		
		if(UserSession::is_authorized()){
			$html .= <<<HTML
						<li>
							<a href="{$root}messages/{$user->vanityURL}" title="Send {$user->firstName} a message">
							<img src="{$root}images/site/icon-contact-mail.png" alt="Send {$user->firstName} a message" />
							<p><strong>WDD Social</strong> Send {$user->firstName} a message</p>
							</a>
						</li>
HTML;
		}
		
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
		
		if(UserSession::is_current($user->id) and $percentage < .5){
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