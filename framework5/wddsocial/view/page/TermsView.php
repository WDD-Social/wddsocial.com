<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class TermsView implements \Framework5\IView {
	
	public function render($options = null) {
		$html .= <<<HTML
				
				<h1 class="mega">Terms of Service</h1>
				<section class="long-content">
HTML;
		$html .= render('wddsocial.view.content.WDDSocial\TermsOfService');
		$html .= <<<HTML

				</section>
HTML;
		return $html;
	}
}