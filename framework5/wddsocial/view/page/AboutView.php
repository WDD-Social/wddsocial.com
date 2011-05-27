<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class AboutView implements \Framework5\IView {
	
	public function render($options = null) {
		
		$html .= <<<HTML
				
				<h1 class="mega">About WDD Social</h1>
				<section class="long-content">
					<p>WDD Social was started in March, 2011, by <a href="/user/" title=""></a>
				</section>
HTML;
		return $html;
	}
}