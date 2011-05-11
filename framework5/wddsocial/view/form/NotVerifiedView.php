<?php

namespace WDDSocial;

class NotVerifiedView implements \Framework5\IView {		
	
	/**
	* Determines what type of content to render
	*/
	
	public function render($email = null) {
		
		return <<<HTML
		You have not yet been verified as a member of the Full Sail University web community.</strong></p>
		<p class="error"><strong>An email has been sent to the Full Sail email account you provided to us ($email) for verification.</strong></p>
		<p class="error"><strong><a href="" title="Resend Verification Email">Resend verification email</a></strong></p>
		<p class="error"><strong><a href="" title="Change your Full Sail Email">Change your Full Sail email</a></strong></p>
		<p class="error"><strong><a href="/contact" title="Contact WDD Social">Contact us for support.</a>

HTML;
	}
}