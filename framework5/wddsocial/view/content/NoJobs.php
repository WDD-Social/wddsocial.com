<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class NoJobs implements \Framework5\IView {
	
	public function render($options = null) {
		switch ($options['type']) {
			case 'all':
				$resultText = 'jobs';
				break;
			case 'internship':
				$resultText = 'internships';
				break;
			default:
				$resultText = "{$options['type']} gigs";
				break;
		}
		return <<<HTML

					<p><strong>Uh oh! We couldn&rsquo;t find any {$resultText}!</strong></p>
HTML;
	}
}