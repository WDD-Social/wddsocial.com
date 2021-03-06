<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ContactFormEmail implements \Framework5\IView {

	public function render($options = null) {
		return <<<HTML

			<h1><a href="http://www.wddsocial.com/" title="WDD Social | Connecting the Full Sail University web community."><img alt="WDD Social | Connecting the Full Sail University web community." src="http://www.wddsocial.com/images/emails/social.logo.png" /></a></h1>
			<h2>WDD Social Feedback</h2>
			<p>From: {$options['name']} (<a href="mailto:{$options['email']}">{$options['email']}</a>)</p>
			<p>{$options['message']}</p>
			
			<p class="signOff">WDD Social Contact Form</p>
HTML;
	}
}