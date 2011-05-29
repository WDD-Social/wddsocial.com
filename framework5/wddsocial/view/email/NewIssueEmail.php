<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class NewIssueEmail implements \Framework5\IView {

	public function render($options = null) {
		
		# format content
		import('wddsocial.controller.WDDSocial\Formatter');
		$options['timestamp'] = Formatter::format_timestamp($options['timestamp']);
		
		return <<<HTML

			<h1><a href="http://www.dev.wddsocial.com/" title="WDD Social | Connecting the Full Sail University web community."><img alt="WDD Social | Connecting the Full Sail University web community." src="http://www.wddsocial.com/images/emails/social.logo.png" /></a></h1>
			<h2>Issue Reported</h2>
			<p>Id: {$options['issue-id']}</p>
			<p>From: {$options['name']} (<a href="mailto:{$options['email']}">{$options['email']}</a>)</p>
			<p>Time: {$options['timestamp']}</p>
			<p>{$options['message']}</p>
			<p><a href="http://dev.wddsocial.com/dev/issues/{$options['issue-id']}">View Issue in the Developer Panel</a></p>
			<p class="signOff">WDD Social Issue Notification</p>
HTML;
	}
}