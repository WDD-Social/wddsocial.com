<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class VerificationView implements \Framework5\IView {

	public function render($options = null) {
		return <<<HTML

			<h1><a href="http://www.dev.wddsocial.com/" title="WDD Social | Connecting the Full Sail University web community."><img alt="WDD Social | Connecting the Full Sail University web community." src="http://www.wddsocial.com/images/emails/social.logo.png" /></a></h1>
			<h2>Please Verify Your WDD Social Account</h2>
			<p>Hey, {$options['firstName']}!</p>
			<p>You recently signed up for WDD Social, but we need to verify that you are part of the Full Sail University web community.</p>
			<p>To do so, please <a href="http://dev.wddsocial.com/verify/{$options['verificationCode']}" title="Verify your WDD Social Account">head over to WDD Social</a>, and you&rsquo;ll be all set!</p>
			<p>Thanks again for being part of our community, and we look forward to getting to know you!</p>
			<p>See you around,</p>
			<p class="signOff">The WDD Social Team</p>
HTML;
	}
}