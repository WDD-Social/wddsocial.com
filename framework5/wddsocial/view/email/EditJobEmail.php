<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class EditJobEmail implements \Framework5\IView {

	public function render($options = null) {
		return <<<HTML

			<h1><a href="http://www.dev.wddsocial.com/" title="WDD Social | Connecting the Full Sail University web community."><img alt="WDD Social | Connecting the Full Sail University web community." src="http://www.wddsocial.com/images/emails/social.logo.png" /></a></h1>
			<h2>Congratulations, your job has been edited!</h2>
			<p>You&rsquo;ve recently edited a job post on <a href="http://wddsocial.com/" title="WDD Social">WDD Social</a>. Please <strong>do not lose this email</strong> because it contains important information regarding editing and deleting your job posting.</p>
			<h2>Edit your Job Post</h2>
			<p><a href="http://dev.wddsocial.com/edit/job/{$options['vanityURL']}/{$options['securityCode']}" title="WDD Social | Edit your Job Post">Click here to edit your job post.</a></p>
			<h2>Delete your Job Post</h2>
			<p><a href="http://dev.wddsocial.com/delete/job/{$options['vanityURL']}/{$options['securityCode']}" title="WDD Social | Delete your Job Post">Click here to delete your job post.</a></p>
			<p>Thank you for contributing to our vibrant community, and we hope to see you again!</p>
			<p class="signOff">WDD Social Team</p>
HTML;
	}
}