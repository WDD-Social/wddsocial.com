<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class NoPosts implements \Framework5\IView {
	
	public function render($options = null) {
		return <<<HTML

					<p class="empty">Uh oh, looks like there is nothing to display here! That&rsquo;s so lonely :(</p>
HTML;
	}
}