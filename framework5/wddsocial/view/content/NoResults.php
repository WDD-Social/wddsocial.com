<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class NoResults implements \Framework5\IView {
	
	public function render($options = null) {
		return <<<HTML

					<p><strong>Uh oh! We couldn&rsquo;t find any {$options['type']} for that search term!</strong></p>
HTML;
	}
}